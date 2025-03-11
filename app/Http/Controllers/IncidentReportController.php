<?php

namespace App\Http\Controllers;

use App\Jobs\Driver\SendIncidentReportNotification;
use App\Models\ActivityLogs;
use App\Models\IncidentReport;
use App\Models\TripTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class IncidentReportController extends Controller
{
    // Driver
    public function index()
    {
        $report = IncidentReport::where('user_id', Auth::id())->get();
        return view('modules.driver.reportIncident.index', compact('report'));
    }

    public function create(TripTicket $tripTicket)
    {
        return view('modules.driver.reportIncident.create', compact('tripTicket'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'reportNumber' => 'required|string|max:255',
            'incident_type' => 'required|string|in:accident,theft,damage,mechanical_issue,traffic',
            'proof' => 'nullable|file|mimes:jpeg,jpg,png|max:8192',
            'description' => 'required|string|max:1000|min:10',
        ]);
    
        $tripTicket = TripTicket::findOrFail($id);
    
        // Check if the trip ticket already has an incident report
        if (IncidentReport::where('trip_ticket_id', $id)->exists()) {
            return redirect()->back()->with('error', 'The trip ticket already has an incident report.');
        }
    
        // Generate a unique report number if the provided one already exists
        $reportNumber = isset($request->reportNumber) && !IncidentReport::where('reportNumber', $request->reportNumber)->exists()
            ? $request->reportNumber
            : strtoupper(Str::random(20));
    
        // Initialize proof to a default value (if needed) to prevent NULL constraint errors
        $proofPath = null;
    
        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            $proofPath = $file->storeAs(
                "incidents/{$tripTicket->user_id}", 
                $reportNumber . '.' . $file->getClientOriginalExtension(), 
                'public'
            );
        }
    
        // Create Incident Report
        $incidentReport = IncidentReport::create([
            'reportNumber'    => $reportNumber,
            'incident_type'   => $request->incident_type,
            'trip_ticket_id'  => $tripTicket->id,
            'vehicle_id'      => $tripTicket->vehicle_id,
            'user_id'         => Auth::id(),
            'description'     => $request->description,
            'approval_status' => 'pending',
            'proof'           => $proofPath, // Assign proof path here directly
        ]);
    
        // Log Activity
        ActivityLogs::create([
            'user_id'    => Auth::id(),
            'event'      => "Incident Report Submitted with Report Number: {$reportNumber} at " . now('Asia/Manila')->format('Y-m-d H:i'),
            'ip_address' => $request->ip(),
        ]);
    
        // Dispatch notification
        SendIncidentReportNotification::dispatch($incidentReport);
    
        return redirect()->route('driver.trip.details',compact('tripTicket'))->with('success', 'Incident report submitted.');
    }
    

    public function details(IncidentReport $incidentReport)
    {
        return view('modules.driver.reportIncident.details', compact('incidentReport'));
    }
}
