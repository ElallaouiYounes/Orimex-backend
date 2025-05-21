<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_id',
        'current_stock',
        'available_stock',
        'allocated_stock',
        'min_stock_level',
        'max_stock_level',
        'location',
        'warehouse',
        'last_updated',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
