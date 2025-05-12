<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'stock_levels',
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
