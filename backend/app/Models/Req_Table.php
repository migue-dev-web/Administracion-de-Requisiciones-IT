<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Req_Table extends Model
{
    protected $table = 'req_table';
    protected $fillable = [
        'problema',
        'requisitor',
        'tecnico',
        'media',
        'descripcion',
        'ubicacion',
        'fecha_creacion',
        'fecha_finalizacion',
    ];


}
