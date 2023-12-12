<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservation';
    protected $fillable = [
        'name', 'table_type', 'people', 'time', 'date', 'phone_number', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
