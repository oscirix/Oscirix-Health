<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicProfile extends Model
{
    use HasFactory, HasUuids;

    // Indicamos el nombre exacto de la tabla en MySQL
    protected $table = 'clinic_profile';

    protected $guarded = [];
}