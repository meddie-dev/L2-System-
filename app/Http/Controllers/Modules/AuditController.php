<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    // Admin

    public function indexAdmin()
    {
        $users = User::role('Vendor')->get();
        return view('modules.admin.vendor.manage', compact('users'));
    }
}
