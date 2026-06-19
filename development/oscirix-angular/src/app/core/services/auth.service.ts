import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
@Injectable({ providedIn: 'root' })
export class AuthService { private readonly api = inject(ApiService); login(payload: unknown) { return this.api.post('/auth/login', payload); } logout() { return this.api.post('/auth/logout', {}); } me() { return this.api.get('/auth/me'); } }

