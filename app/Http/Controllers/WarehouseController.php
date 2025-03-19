<?php

namespace App\Http\Controllers;

use App\Jobs\Admin\SendRestockNotification;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        // Get all products
        $products = Product::all();

        // Get demand data for all products
        $demandProducts = DB::table('order_products')
            ->selectRaw('product_id, sum(quantity) as demand')
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id')
            ->toArray();

        // Map demand data to products
        $products = $products->map(function ($product) use ($demandProducts) {
            $product->demand = isset($demandProducts[$product->id]) ? $demandProducts[$product->id]->demand : 0;
            return $product;
        });
        
        return view('modules.admin.warehouse.index', compact('products'));
    }

    public function create(Product $product)
    {
        return view('modules.admin.warehouse.create', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);
    
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'weight' => $request->weight,
            'description' => $request->description,
        ]);
    
        // Schedule stock update + notification for 1 day later
        if ($request->stock > 0) {
            // Dispatch job to update stock and notify admins/staff after 1 day
            SendRestockNotification::dispatch($product, $request->stock)->delay(Carbon::now()->addDay(1));
        }
    
        return redirect()->route('admin.warehouse.index')->with('success', 'Product restock request successfully scheduled.');
    }
}
