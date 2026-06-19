import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { ClinicalRecord } from '../models/oscirix.models';

@Injectable({ providedIn: 'root' })
export class ClinicalRecordService {
  private readonly api = inject(ApiService);
  list(patientId: string) { return this.api.get<ClinicalRecord[]>(`/patients/${patientId}/clinical-records`); }
  create(patientId: string, payload: unknown) { return this.api.post<ClinicalRecord>(`/patients/${patientId}/clinical-records`, payload); }
  show(recordId: string) { return this.api.get<ClinicalRecord>(`/clinical-records/${recordId}`); }
  close(recordId: string) { return this.api.post(`/clinical-records/${recordId}/close`, {}); }
}
