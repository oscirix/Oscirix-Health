<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentMedia extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'treatment_media';

    protected $guarded = [];

    // Opcional: Relación con el tratamiento
    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}