<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'leads'; // Nombre exacto de la tabla en phpMyAdmin

    protected $guarded = []; // Permite guardar todos los campos definidos en la migración/base
}