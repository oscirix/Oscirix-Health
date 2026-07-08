<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $keyType = 'string';
    public $incrementing = false;

    // Campos masivos basados en las columnas reales que se ven en tu phpMyAdmin
    protected $fillable = [
        'id',
        'clinic_id',
        'hcu_number',
        'document_id',
        'first_last_name',
        'second_last_name',
        'first_name',
        'second_name',
        'full_name',
        'sex',
        'birth_date',
        'blood_type',
        'whatsapp_phone',
        'phone_extension',
        'email',
        'address',
        'city',
        'occupation',
        'avatar_url',
        'source',
    ];

    // Genera el UUID automáticamente antes de registrar un paciente
    protected static function booted()
    {
        static::creating(function ($patient) {
            if (empty($patient->id)) {
                $patient->id = (string) Str::uuid();
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(ClinicProfile::class, 'clinic_id');
    }
}