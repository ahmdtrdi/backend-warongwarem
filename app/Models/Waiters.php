<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiters extends Model
{
    use HasFactory;

    protected $tables = 'waiters';
    protected $fillable = [
        'username',
        'user_id'
    ];
}
