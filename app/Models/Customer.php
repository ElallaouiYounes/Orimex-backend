<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'company_type',
        'location',
        'status',
    ];
    
    public function orders()
    {
    return $this->hasMany(Order::class);
    }

}
