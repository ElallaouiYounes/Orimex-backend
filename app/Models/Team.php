<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'department',
        'role',
        'status',
        'password', // Add password here
    ];

    // Hash password before saving it
    protected static function booted()
    {
        static::creating(function ($team) {
            $team->password = Hash::make($team->password); // hash the password
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'employee_id');
    }
}
