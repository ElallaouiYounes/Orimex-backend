<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'customer_name',
        'amount',
        'payment_method',
        'status',
        'invoice_number',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
