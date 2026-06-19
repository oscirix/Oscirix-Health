import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { Prescription } from '../models/oscirix.models';

@Injectable({ providedIn: 'root' })
export class PrescriptionService {
  private readonly api = inject(ApiService);

  list(recordId: string) {
    return this.api.get<Prescription[]>(`/clinical-records/${recordId}/prescriptions`);
  }

  create(recordId: string, payload: Partial<Prescription>) {
    return this.api.post<Prescription>(`/clinical-records/${recordId}/prescriptions`, payload);
  }

  show(id: string) {
    return this.api.get<Prescription>(`/prescriptions/${id}`);
  }

  suspend(id: string) {
    return this.api.patch<Prescription>(`/prescriptions/${id}/suspend`, {});
  }
}
