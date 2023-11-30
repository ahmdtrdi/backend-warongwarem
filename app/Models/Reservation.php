<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $fillable = [
        'table_type', 'people', 'time', 'date', 'phone_number', 'role', 'user_id', 'status','name'


    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
