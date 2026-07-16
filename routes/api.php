<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClinicSettingsController;
use App\Http\Controllers\Api\ClinicalRecordAnamnesisController;
use App\Http\Controllers\Api\ClinicalRecordController;
use App\Http\Controllers\Api\ClinicalRecordDiagnosisPlanController;
use App\Http\Controllers\Api\ClinicalRecordEvolutionController;
use App\Http\Controllers\Api\ClinicalRecordExamController;
use App\Http\Controllers\Api\ClinicalRecordOdontogramController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ExamRequestController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PatientDocumentController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\Api\ClinicProfileController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\WorkHourController;
use App\Http\Controllers\Api\TreatmentMediaController;
use App\Http\Controllers\Api\NotificationController;

use App\Http\Controllers\Api\ClinicalRecordDentalFindingController;
use App\Http\Controllers\Api\ClinicalRecordPeriodontalIndexController;
use App\Http\Controllers\Api\ClinicalRecordTreatmentPlanController;

use App\Http\Controllers\Api\ClinicalRecordTreatmentPlanStepController;
use App\Http\Controllers\Api\ClinicalRecordVitalSignController;

use App\Http\Controllers\Api\PatientAlertController;
use App\Http\Controllers\Api\PatientAllergyController;
use App\Http\Controllers\Api\PatientHabitController;
use App\Http\Controllers\Api\PatientMedicalConditionController;

use App\Http\Controllers\Api\ExamFileController;

use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTAS PÚBLICAS (No requieren Login)
// ==========================================
Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']); // El login DEBE ser público
});

// ==========================================
// 2. RUTAS PROTEGIDAS (Requieren Token Sanctum válido)
// ==========================================
Route::middleware('auth:sanctum')->group(function (): void {

    // Rutas de Auth que sí necesitan sesión iniciada para destruir o leer el token
    Route::prefix('auth')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Toda la lógica médica protegida del sistema
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);

    Route::apiResource('patients', PatientController::class)->only(['index', 'store', 'show', 'update']);
    Route::get('patients/{patient}/summary', [PatientController::class, 'summary']);
    Route::get('patients/{patient}/alerts', [PatientController::class, 'alerts']);
    Route::get('clinic-profile', [ClinicProfileController::class, 'show']);
    Route::put('clinic-profile', [ClinicProfileController::class, 'update']);

    Route::get('patients/{patient}/clinical-records', [ClinicalRecordController::class, 'index']);
    Route::post('patients/{patient}/clinical-records', [ClinicalRecordController::class, 'store']);
    Route::get('clinical-records/{clinical_record}', [ClinicalRecordController::class, 'show']);
    Route::patch('clinical-records/{clinical_record}', [ClinicalRecordController::class, 'update']);
    Route::post('clinical-records/{clinical_record}/close', [ClinicalRecordController::class, 'close']);

    Route::get('clinical-records/{clinical_record}/anamnesis', [ClinicalRecordAnamnesisController::class, 'show']);
    Route::put('clinical-records/{clinical_record}/anamnesis', [ClinicalRecordAnamnesisController::class, 'upsert']);

    Route::get('clinical-records/{clinical_record}/clinical-exam', [ClinicalRecordExamController::class, 'show']);
    Route::put('clinical-records/{clinical_record}/clinical-exam', [ClinicalRecordExamController::class, 'upsert']);

    Route::get('clinical-records/{clinical_record}/odontogram', [ClinicalRecordOdontogramController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/odontogram', [ClinicalRecordOdontogramController::class, 'store']);
    Route::patch('odontogram-items/{odontogram_item}', [ClinicalRecordOdontogramController::class, 'update']);
    Route::delete('odontogram-items/{odontogram_item}', [ClinicalRecordOdontogramController::class, 'destroy']);

    Route::get('clinical-records/{clinical_record}/diagnosis-plan', [ClinicalRecordDiagnosisPlanController::class, 'show']);
    Route::post('clinical-records/{clinical_record}/diagnoses', [ClinicalRecordDiagnosisPlanController::class, 'storeDiagnosis']);
    Route::post('clinical-records/{clinical_record}/treatment-plans', [ClinicalRecordDiagnosisPlanController::class, 'storeTreatmentPlan']);
    Route::patch('treatment-plan-steps/{step}', [ClinicalRecordDiagnosisPlanController::class, 'updateStep']);

    Route::get('clinical-records/{clinical_record}/evolutions', [ClinicalRecordEvolutionController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/evolutions', [ClinicalRecordEvolutionController::class, 'store']);

    Route::get('clinical-records/{clinical_record}/prescriptions', [PrescriptionController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/prescriptions', [PrescriptionController::class, 'store']);
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show']);
    Route::get('prescriptions/{prescription}/pdf', [PrescriptionController::class, 'pdf']);
    Route::patch('prescriptions/{prescription}/status', [PrescriptionController::class, 'updateStatus']);

    Route::get('clinical-records/{clinical_record}/exams', [ExamRequestController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/exams', [ExamRequestController::class, 'store']);

    Route::get('exams', [ExamRequestController::class, 'index']);
    Route::post('exams', [ExamRequestController::class, 'store']);

    /*Route::get('exams/{exam_request}', [ExamRequestController::class, 'show']);
    Route::patch('exams/{exam_request}/result', [ExamRequestController::class, 'updateResult']);
    Route::post('exams/{exam_request}/files', [ExamRequestController::class, 'storeFile']);
    Route::get('exam-files/{exam_file}/download', [ExamRequestController::class, 'downloadFile']);
    */

    // Subir archivo a una solicitud específica
    Route::post('exams/{exam_request}/files', [ExamFileController::class, 'store']);

    // Descargar un archivo por su ID
    Route::get('exam-files/{exam_file}/download', [ExamFileController::class, 'download']);

    // Borrar un archivo por su ID
    Route::delete('exam-files/{exam_file}', [ExamFileController::class, 'destroy']);


    Route::get('clinical-records/{clinical_record}/documents', [PatientDocumentController::class, 'indexByClinicalRecord']);
    Route::get('patients/{patient}/documents', [PatientDocumentController::class, 'indexByPatient']);
    Route::post('patients/{patient}/documents', [PatientDocumentController::class, 'store']);
    Route::get('documents/{patient_document}/download', [PatientDocumentController::class, 'download']);
    Route::patch('documents/{patient_document}', [PatientDocumentController::class, 'update']);
    Route::delete('documents/{patient_document}', [PatientDocumentController::class, 'destroy']);

    Route::apiResource('appointments', AppointmentController::class)->only(['index', 'store', 'update']);

    Route::apiResource('leads', LeadController::class)->only(['index', 'store', 'update']);

    Route::get('clinic/settings', [ClinicSettingsController::class, 'show']);
    Route::patch('clinic/settings', [ClinicSettingsController::class, 'update']);

    Route::apiResource('treatments', TreatmentController::class);

    //Nuevas rutas
    Route::apiResource('testimonials', TestimonialController::class);

    Route::get('work-hours', [WorkHourController::class, 'index']);
    Route::patch('work-hours/{workHour}', [WorkHourController::class, 'update']);

    Route::get('treatment-media', [TreatmentMediaController::class, 'index']);
    Route::get('treatment-media/{treatmentMedia}', [TreatmentMediaController::class, 'show']);

    Route::apiResource('notifications', NotificationController::class);

    Route::get('clinical-records/{clinical_record}/dental-findings', [ClinicalRecordDentalFindingController::class, 'show']);
    Route::put('clinical-records/{clinical_record}/dental-findings', [ClinicalRecordDentalFindingController::class, 'upsert']);

    Route::get('clinical-records/{clinical_record}/periodontal-indices', [ClinicalRecordPeriodontalIndexController::class, 'show']);
    Route::put('clinical-records/{clinical_record}/periodontal-indices', [ClinicalRecordPeriodontalIndexController::class, 'storeOrUpdate']);

    Route::get('clinical-records/{clinical_record}/treatment-plans', [ClinicalRecordTreatmentPlanController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/treatment-plans', [ClinicalRecordTreatmentPlanController::class, 'store']);
    Route::delete('treatment-plans/{id}', [ClinicalRecordTreatmentPlanController::class, 'destroy']);

    Route::get('treatment-plans/{treatment_plan}/steps', [ClinicalRecordTreatmentPlanStepController::class, 'index']);
    Route::post('treatment-plans/{treatment_plan}/steps', [ClinicalRecordTreatmentPlanStepController::class, 'store']);
    Route::patch('treatment-plan-steps/{id}', [ClinicalRecordTreatmentPlanStepController::class, 'update']);
    Route::delete('treatment-plan-steps/{id}', [ClinicalRecordTreatmentPlanStepController::class, 'destroy']);

    Route::get('clinical-records/{clinical_record}/vital-signs', [ClinicalRecordVitalSignController::class, 'index']);
    Route::post('clinical-records/{clinical_record}/vital-signs', [ClinicalRecordVitalSignController::class, 'store']);
    Route::delete('vital-signs/{id}', [ClinicalRecordVitalSignController::class, 'destroy']);

    
    Route::get('exam-requests/{exam_request}/files', [ExamFileController::class, 'index']);
    Route::post('exam-requests/{exam_request}/files', [ExamFileController::class, 'store']);
    Route::delete('exam-files/{id}', [ExamFileController::class, 'destroy']);

    // Rutas para las alertas de pacientes
    Route::get('patients/{patient}/alerts', [PatientAlertController::class, 'index']);
    Route::post('patients/{patient}/alerts', [PatientAlertController::class, 'store']);
    Route::delete('patient-alerts/{id}', [PatientAlertController::class, 'destroy']);

    // Rutas para las alergias de pacientes
    Route::get('patients/{patient}/allergies', [PatientAllergyController::class, 'index']);
    Route::post('patients/{patient}/allergies', [PatientAllergyController::class, 'store']);
    Route::delete('patient-allergies/{id}', [PatientAllergyController::class, 'destroy']);

    // Rutas para los hábitos de pacientes
    Route::get('patients/{patient}/habits', [PatientHabitController::class, 'index']);
    Route::post('patients/{patient}/habits', [PatientHabitController::class, 'store']);
    Route::delete('patient-habits/{id}', [PatientHabitController::class, 'destroy']);

    // Rutas para las condiciones médicas de pacientes
    Route::get('patients/{patient}/medical-conditions', [PatientMedicalConditionController::class, 'index']);
    Route::post('patients/{patient}/medical-conditions', [PatientMedicalConditionController::class, 'store']);
    Route::delete('patient-medical-conditions/{id}', [PatientMedicalConditionController::class, 'destroy']);


});