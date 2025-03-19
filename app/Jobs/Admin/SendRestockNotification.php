<?php

namespace App\Jobs\Admin;

use App\Models\InventoryRecord;
use App\Models\User;
use App\Models\Product;
use App\Notifications\NewNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRestockNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $stock;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, $stock)
    {
        $this->product = $product;
        $this->stock = $stock;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Step 1: Get the original stock before update
        $originalStock = $this->product->stock;

        // Step 2: Update the stock
        $newStock = $originalStock + $this->stock;
        $this->product->update(['stock' => $newStock]);

        // Step 3: Calculate how much was actually added
        $quantityAdded = $newStock - $originalStock;

        // Step 4: Log the inventory change in database
        InventoryRecord::create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'quantity_added' => $quantityAdded,
            'updated_at' => now(),
        ]);

        // Step 5: Notify Admins & Staff
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        $staffs = User::whereHas('roles', function ($query) {
            $query->where('name', 'Staff');
        })->get();

        $message = "Product inventory for {$this->product->name} has been updated with an additional {$quantityAdded} units.";

        foreach ($admins as $admin) {
            $admin->notify(new NewNotification($message));
        }

        foreach ($staffs as $staff) {
            $staff->notify(new NewNotification($message));
        }
    }
}