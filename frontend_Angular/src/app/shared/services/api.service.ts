import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Shift, ShiftResponse } from '../models/shift.model';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private baseUrl = 'http://localhost:8005/api';

  constructor(private http: HttpClient) {}

  // Shift endpoints
  getShifts(page: number = 1, perPage: number = 10): Observable<ShiftResponse> {
    const params = new HttpParams()
      .set('page', page.toString())
      .set('per_page', perPage.toString());
    return this.http.get<ShiftResponse>(`${this.baseUrl}/shifts`, { params });
  }

  getShift(id: number): Observable<Shift> {
    return this.http.get<Shift>(`${this.baseUrl}/shifts/${id}`);
  }

  createShift(shift: { carer_id: number; client_id: number; start_time: string; end_time: string }): Observable<Shift> {
    return this.http.post<Shift>(`${this.baseUrl}/shifts`, shift);
  }

  updateShift(id: number, shift: { carer_id: number; client_id: number; start_time: string; end_time: string }): Observable<Shift> {
    return this.http.put<Shift>(`${this.baseUrl}/shifts/${id}`, shift);
  }

  deleteShift(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/shifts/${id}`);
  }

  // Carer endpoints
  getCarers(): Observable<{ id: number; name: string; phone?: string }[]> {
    return this.http.get<{ id: number; name: string; phone?: string }[]>(`${this.baseUrl}/carers`);
  }

  // Client endpoints
  getClients(): Observable<{ id: number; name: string; phone?: string }[]> {
    return this.http.get<{ id: number; name: string; phone?: string }[]>(`${this.baseUrl}/clients`);
  }
} 