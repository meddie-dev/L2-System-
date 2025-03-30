<?php

namespace App\Http\Controllers\Modules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Staff\Order\SendOrderApprovalNotification;
use App\Jobs\Staff\Order\SendRejectionNotification;
use App\Jobs\Vendor\SendOrderNotifications;
use App\Models\ActivityLogs;
use App\Models\Modules\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return view('modules.vendor.order.index', compact('orders'));
    }

    public function create(User $user)
    {
        $products = Product::all();
        return view('modules.vendor.order.create', compact('user', 'products'));
    }

    public function store(Request $request, User $user)
    {
        Log::info('Store method started', ['request' => $request->all()]);
    
        try {
            $request->merge([
                'products' => collect(json_decode($request->input('products'), true))
                    ->mapWithKeys(function ($product) {
                        return [
                            $product['id'] => [
                                'id' => $product['id'],
                                'name' => $product['name'],
                                'quantity' => $product['quantity'],
                                'price' => $product['price'],
                                'weight' => $product['weight']
                            ]
                        ];
                    })
                    ->toArray(),
            ]);

            $validatedData = $request->validate([
                'orderNumber' => 'required|string|max:255',
                'quantity' => 'required|numeric|max:2000',
                'deliveryAddress' => 'required|string|max:255',
                'deliveryRequestDate' => 'required|date',
                'specialInstructions' => 'nullable|string|max:1000',
                'weight' => 'required|numeric|max:999999.99',
                'amount' => 'required|numeric|max:999999.99',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.weight' => 'required|numeric|min:0',
            ]);
    
            Log::info('Validation passed', ['validatedData' => $validatedData]);
    
            $orderNumber = Order::where('orderNumber', $request->orderNumber)->exists()
                ? strtoupper(Str::random(20))
                : $request->orderNumber;
    
            $order = Order::create([
                'user_id' => $user->id,
                'orderNumber' => $orderNumber,
                'quantity' => $request->quantity,
                'weight' => $request->weight,
                'amount' => $request->amount,
                'products' => json_encode($request->products),
                'deliveryAddress' => $request->deliveryAddress,
                'deliveryRequestDate' => $request->deliveryRequestDate,
                'specialInstructions' => $request->specialInstructions,
                'approval_status' => 'pending'
            ]);
    
            Log::info('Order created', ['order_id' => $order->id]);
    
         
            if ($request->has('products')) {
                $order->products()->sync(collect($request->products)->mapWithKeys(function ($details) {
                    return [$details['id'] => [
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                        'weight' => $details['weight']
                    ]];
                })->toArray());
                foreach ($request->products as $productId => $details) {
                    $product = Product::findOrFail($productId);
                    $product->stock -= $details['quantity'];
                    $product->save();
                }
                Log::info('Products synced and stock updated');
            }
    
            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Order Submitted with Order Number: {$order->orderNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => $request->ip(),
            ]);
    
            SendOrderNotifications::dispatch($order, $user);
            Log::info('Notification dispatched');
    
            return redirect()->route('vendorPortal.order.document.new', ['order' => $order->id]);
    
        } catch (\Exception $e) {
            Log::error('Error in store method', ['error' => $e->getMessage()]);
            return back()->withErrors('Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit(Order $order)
    {
        // Load the products relationship to ensure it's an Eloquent collection
        $order->load('products');
    
        $products = Product::all();
    
        $selectedProducts = $order->products()->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price,
                'weight' => $product->pivot->weight,
            ];
        });
        
    
        return view('modules.vendor.order.edit', compact('order', 'products', 'selectedProducts'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'orderNumber' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:255',
            'deliveryAddress' => 'required|string|max:255',
            'deliveryRequestDate' => 'required|date',
            'specialInstructions' => 'nullable|string|max:1000',
            'weight' => 'required|numeric|max:999999.99',
            'amount' => 'required|numeric|max:999999999.99',
            'products' => 'required|json',
        ]);
    
        $selectedProducts = json_decode($request->products, true);
    
        // Restore stock for currently attached products
        $order->products()->get()->each(function ($existingProduct) {
            $product = Product::find($existingProduct->pivot->product_id);
            if ($product) {
                $product->stock += $existingProduct->pivot->quantity;
                $product->save();
            }
        });
    
        // Detach existing products
        $order->products()->detach();
    
        // Attach new products and update stock
        foreach ($selectedProducts as $productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $product->stock -= $productData['quantity'];
                $product->save();
                $order->products()->attach($productData['id'], [
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                    'weight' => $productData['weight']
                ]);
            }
        }
    
        $order->update([
            'orderNumber' => $request->orderNumber,
            'quantity' => $request->quantity,
            'deliveryAddress' => $request->deliveryAddress,
            'deliveryRequestDate' => $request->deliveryRequestDate,
            'specialInstructions' => $request->specialInstructions,
            'weight' => $request->weight,
            'products' => $request->products,
            'amount' => $request->amount,
            'approval_status' => 'pending',
            'approved_by' => null
        ]);
    
        ActivityLogs::create([
            'user_id' => Auth::id(),
            'event' => "Updated Order Request: {$order->orderNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
            'ip_address' => $request->ip(),
        ]);
    
        return redirect()->route('vendorPortal.order.document.edit', ['order' => $order->id]);
    }
    
    public function details(Order $order)
    {
        return view('modules.vendor.order.details', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orderPortal.order')->with('success', 'Order request deleted successfully.');
    }

    // STAFF SECTION

    public function manage()
    {
        $orders = Order::where('assigned_to', Auth::id())->get();
        return view('modules.staff.vendor.manage', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('modules.staff.vendor.show', compact('order'));
    }

    public function approve(Order $order)
    {
        DB::beginTransaction();

        try {
            $order->update([
                'approval_status' => 'reviewed',
                'reviewed_by' => auth()->id(),
                'rejected_by' => null
            ]);
    
            DB::commit();
    
            // Dispatch job asynchronously
            SendOrderApprovalNotification::dispatch($order);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Reviewed Order Request: {$order->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);
    
            return redirect()->route('staff.vendors.manage')->with('success', 'Order reviewed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function reject(Order $order)
    {
        DB::beginTransaction();

        try {
            $order->update([
                'approval_status' => 'rejected',
                'rejected_by' => auth()->id(),
            ]);

            SendRejectionNotification::dispatch($order);

            ActivityLogs::create([
                'user_id' => Auth::id(),
                'event' => "Rejected Order Request: {$order->orderNumber} in time of: " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('staff.vendors.manage')->with('success', 'Order rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    // Super Admin

    public function indexSA()
    {
        $users = User::role('Vendor')->get();
        return view('modules.superAdmin.vendor.index', compact('users'));
    }

    public function showSA(User $user)
    {
        $orders = $user->order()->get();
        $payments = $user->payment()->get();
        $documents = $user->document()->get();
        $vehicleReservations = $user->vehicleReservations()->get();
        return view('modules.superAdmin.vendor.show', compact('user', 'orders', 'payments', 'documents', 'vehicleReservations'));
    }

}
