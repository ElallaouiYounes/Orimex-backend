<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barcode',
        'name',
        'category',
        'thickness',
        'color',
        'dimensions',
        'price',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id', 'id');
    }
}
