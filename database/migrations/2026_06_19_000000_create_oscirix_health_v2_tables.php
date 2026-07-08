<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clinic_profile', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('brand_name', 150)->nullable();
            $table->string('timezone', 80)->default('America/Guayaquil');
            $table->timestamps();
        });
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinic_id')->nullable();
            $table->string('hcu_number', 80)->nullable()->index();
            $table->string('document_id', 80)->nullable()->index();
            $table->string('full_name', 180)->index();
            $table->string('sex', 20)->default('unknown');
            $table->date('birth_date')->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->string('whatsapp_phone', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamps();
        });
        Schema::create('patient_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->index();
            $table->string('alert_type', 60);
            $table->string('severity', 40)->default('low');
            $table->string('title', 160);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('clinical_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->index();
            $table->uuid('appointment_id')->nullable()->index();
            $table->string('record_type', 60)->default('clinic_visit');
            $table->string('origin', 40)->default('internal');
            $table->dateTime('record_date')->index();
            $table->string('title', 180)->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('current_illness')->nullable();
            $table->string('status', 40)->default('draft');
            $table->dateTime('signed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('clinical_record_anamnesis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinical_record_id')->index();
            $table->string('section', 80);
            $table->text('content')->nullable();
            $table->timestamps();
        });
        Schema::create('clinical_record_odontogram_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinical_record_id')->index();
            $table->string('tooth_number', 5);
            $table->string('surface', 20)->default('none');
            $table->string('finding_type', 80);
            $table->string('finding_status', 40)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        Schema::create('clinical_record_diagnoses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinical_record_id')->index();
            $table->string('tooth_number', 5)->nullable();
            $table->text('diagnosis_text');
            $table->string('icd10_code', 20)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinical_record_id')->index();
            $table->uuid('patient_id')->index();
            $table->string('status', 40)->default('draft');
            $table->dateTime('prescribed_at')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();
        });
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prescription_id')->index();
            $table->string('medication_name', 180);
            $table->string('frequency', 120)->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
        Schema::create('exam_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('clinical_record_id')->index();
            $table->uuid('patient_id')->index();
            $table->string('exam_category', 80);
            $table->string('exam_type', 160);
            $table->text('result_notes')->nullable();
            $table->string('status', 40)->default('requested');
            $table->timestamps();
        });
        Schema::create('exam_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_request_id')->index();
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('mime_type', 120)->nullable();
            $table->timestamps();
        });
        Schema::create('patient_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->index();
            $table->uuid('clinical_record_id')->nullable()->index();
            $table->string('document_category', 80)->default('other');
            $table->string('title', 180);
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('mime_type', 120)->nullable();
            $table->string('signed_status', 40)->default('not_required');
            $table->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('action', 120);
            $table->string('subject_type', 160)->nullable();
            $table->uuid('subject_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        foreach (['activity_logs','patient_documents','exam_files','exam_requests','prescription_items','prescriptions','clinical_record_diagnoses','clinical_record_odontogram_items','clinical_record_anamnesis','clinical_records','patient_alerts','patients','clinic_profile'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
