<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRequest extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];
    // Añade esta relación
    public function files()
    {
        return $this->hasMany(ExamFile::class, 'exam_request_id');
    }
}

