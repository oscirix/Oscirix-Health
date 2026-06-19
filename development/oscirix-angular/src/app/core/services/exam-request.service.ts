import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { ExamRequest } from '../models/oscirix.models';

@Injectable({ providedIn: 'root' })
export class ExamRequestService {
  private readonly api = inject(ApiService);

  list(recordId: string) {
    return this.api.get<ExamRequest[]>(`/clinical-records/${recordId}/exams`);
  }

  create(recordId: string, payload: Partial<ExamRequest>) {
    return this.api.post<ExamRequest>(`/clinical-records/${recordId}/exams`, payload);
  }

  show(id: string) {
    return this.api.get<ExamRequest>(`/exams/${id}`);
  }

  attachResult(id: string, payload: FormData) {
    return this.api.post<ExamRequest>(`/exams/${id}/files`, payload);
  }
}
