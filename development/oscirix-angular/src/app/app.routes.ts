import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: '', loadComponent: () => import('./public/pages/home/home').then((m) => m.HomePage) },
  { path: 'login', loadComponent: () => import('./auth/pages/login/login').then((m) => m.LoginPage) },
  {
    path: 'dashboard',
    loadComponent: () => import('./dashboard/layout/dashboard-layout/dashboard-layout').then((m) => m.DashboardLayout),
    children: [
      { path: '', pathMatch: 'full', redirectTo: 'resumen' },
      { path: 'resumen', loadComponent: () => import('./dashboard/pages/resume/resume').then((m) => m.ResumePage) },
      { path: 'calendario', loadComponent: () => import('./dashboard/pages/calendar/calendar').then((m) => m.CalendarPage) },
      { path: 'leads', loadComponent: () => import('./dashboard/pages/leads/leads').then((m) => m.LeadsPage) },
      { path: 'configuracion', loadComponent: () => import('./dashboard/pages/configuration/configuration').then((m) => m.ConfigurationPage) },
      { path: 'pacientes', loadComponent: () => import('./dashboard/pages/patients/patient-list/patient-list').then((m) => m.PatientListPage) },
      { path: 'pacientes/:patient_id', loadComponent: () => import('./dashboard/pages/patients/patient-detail/patient-detail').then((m) => m.PatientDetailPage) },
      { path: 'pacientes/:patient_id/historia-clinica', loadComponent: () => import('./dashboard/pages/patients/clinical-records/clinical-record-list/clinical-record-list').then((m) => m.ClinicalRecordListPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id', loadComponent: () => import('./dashboard/pages/patients/clinical-records/clinical-record-detail/clinical-record-detail').then((m) => m.ClinicalRecordDetailPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/resumen', loadComponent: () => import('./dashboard/pages/patients/clinical-records/clinical-record-summary/clinical-record-summary').then((m) => m.ClinicalRecordSummaryPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/anamnesis', loadComponent: () => import('./dashboard/pages/patients/clinical-records/anamnesis/anamnesis').then((m) => m.AnamnesisPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/examen-clinico', loadComponent: () => import('./dashboard/pages/patients/clinical-records/clinical-exam/clinical-exam').then((m) => m.ClinicalExamPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/odontograma', loadComponent: () => import('./dashboard/pages/patients/clinical-records/odontogram/odontogram').then((m) => m.OdontogramPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/diagnostico-plan', loadComponent: () => import('./dashboard/pages/patients/clinical-records/diagnosis-plan/diagnosis-plan').then((m) => m.DiagnosisPlanPage) },
      { path: 'pacientes/:patient_id/historia-clinica/:clinical_record_id/evolucion', loadComponent: () => import('./dashboard/pages/patients/clinical-records/evolution/evolution').then((m) => m.EvolutionPage) },
      { path: 'pacientes/:patient_id/prescripciones', loadComponent: () => import('./dashboard/pages/patients/prescriptions/prescriptions').then((m) => m.PrescriptionsPage) },
      { path: 'pacientes/:patient_id/examenes', loadComponent: () => import('./dashboard/pages/patients/exams/exams').then((m) => m.ExamsPage) },
      { path: 'pacientes/:patient_id/documentos', loadComponent: () => import('./dashboard/pages/patients/documents/documents').then((m) => m.DocumentsPage) },
    ],
  },
  { path: '**', redirectTo: '' },
];
