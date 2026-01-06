<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockLow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Product $product;
    public int $currentStock;
    public int $threshold;

    public function __construct(Product $product, int $currentStock, int $threshold)
    {
        $this->product = $product;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
    }
}
