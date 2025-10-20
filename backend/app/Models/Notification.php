<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'req_id',
        'mensaje',
        'leida'
    ];

    protected $attributes = [
        'leida' => false
    ];
}