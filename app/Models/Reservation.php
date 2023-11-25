<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $tables = 'reservations';
    protected $fillable = [
        'table_type', 'people', 'time', 'date', 'phone_number', 'customer_id'
    ];
}
