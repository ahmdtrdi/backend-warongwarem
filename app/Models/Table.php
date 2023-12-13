<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $table = 'table_list';
    protected $tableId = 'table_id'; // default: 'id
    protected $fillable = [
        'on_used',
    ];
}
