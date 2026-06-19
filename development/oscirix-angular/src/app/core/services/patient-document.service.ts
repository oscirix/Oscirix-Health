import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { PatientDocument } from '../models/oscirix.models';

@Injectable({ providedIn: 'root' })
export class PatientDocumentService {
  private readonly api = inject(ApiService);

  list(patientId: string) {
    return this.api.get<PatientDocument[]>(`/patients/${patientId}/documents`);
  }

  upload(patientId: string, payload: FormData) {
    return this.api.post<PatientDocument>(`/patients/${patientId}/documents`, payload);
  }

  show(id: string) {
    return this.api.get<PatientDocument>(`/documents/${id}`);
  }

  destroy(id: string) {
    return this.api.delete(`/documents/${id}`);
  }
}
