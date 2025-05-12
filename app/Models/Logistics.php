<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'license_plate',
        'type',
        'capacity',
        'current_location',
        'status',
        'driver',
        'last_maintenance',
        'next_maintenance',
        'model',
    ];
}
