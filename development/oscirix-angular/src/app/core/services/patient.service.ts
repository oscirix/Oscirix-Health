import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { Patient } from '../models/oscirix.models';

@Injectable({ providedIn: 'root' })
export class PatientService {
  private readonly api = inject(ApiService);
  list() { return this.api.get<Patient[]>('/patients'); }
  show(patientId: string) { return this.api.get<Patient>(`/patients/${patientId}`); }
  summary(patientId: string) { return this.api.get(`/patients/${patientId}/summary`); }
  alerts(patientId: string) { return this.api.get(`/patients/${patientId}/alerts`); }
}
