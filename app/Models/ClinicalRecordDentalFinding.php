<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalRecordDentalFinding extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'clinical_record_dental_findings';

    protected $guarded = [];
}