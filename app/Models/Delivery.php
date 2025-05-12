<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }

    public function employee()
    {
        return $this->belongsTo(Team::class, 'employee_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Logistic::class, 'vehicle_id');
    }
}
