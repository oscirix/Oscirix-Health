import { HttpClient } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class ApiService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = '/api';
  get<T>(url: string) { return this.http.get<T>(`${this.baseUrl}${url}`); }
  post<T>(url: string, body: unknown) { return this.http.post<T>(`${this.baseUrl}${url}`, body); }
  put<T>(url: string, body: unknown) { return this.http.put<T>(`${this.baseUrl}${url}`, body); }
  patch<T>(url: string, body: unknown) { return this.http.patch<T>(`${this.baseUrl}${url}`, body); }
  delete<T>(url: string) { return this.http.delete<T>(`${this.baseUrl}${url}`); }
}
