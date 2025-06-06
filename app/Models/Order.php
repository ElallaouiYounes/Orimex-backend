<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'expected_delivery',
        'status',
        'total_price',
    ];
    
    public function customer()
    {
    return $this->belongsTo(Customer::class);
    }

    public function product()
    {
    return $this->belongsTo(Product::class);
    }

}
