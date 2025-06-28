import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ApiService } from '../shared/services/api.service';
import { Shift } from '../shared/models/shift.model';
import { Modal } from 'bootstrap';

interface Carer { id: number; name: string; phone?: string; }
interface Client { id: number; name: string; phone?: string; }

@Component({
  selector: 'app-shifts',
  templateUrl: './shifts.component.html',
  styleUrls: ['./shifts.component.css'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule]
})
export class ShiftsComponent implements OnInit {
  shifts: Shift[] = [];
  page = 1;
  perPage = 10;
  totalPages = 1;
  total = 0;
  loading = false;
  error = '';

  carers: Carer[] = [];
  clients: Client[] = [];

  // Modal
  @ViewChild('shiftModal') shiftModalRef!: ElementRef;
  @ViewChild('deleteModal') deleteModalRef!: ElementRef;
  shiftModal: any;
  deleteModal: any;

  // Form
  shiftForm: FormGroup;
  editingShift: Shift | null = null;
  formError = '';
  saving = false;
  deleting = false;
  deleteTarget: Shift | null = null;

  constructor(
    private api: ApiService,
    private fb: FormBuilder
  ) {
    this.shiftForm = this.fb.group({
      carer_id: ['', Validators.required],
      client_id: ['', Validators.required],
      start_time: ['', Validators.required],
      end_time: ['', Validators.required],
      notes: ['']
    });
  }

  ngOnInit(): void {
    this.loadShifts();
    this.loadCarers();
    this.loadClients();
  }

  ngAfterViewInit(): void {
    this.shiftModal = new Modal(this.shiftModalRef.nativeElement);
    this.deleteModal = new Modal(this.deleteModalRef.nativeElement);
  }

  loadShifts(): void {
    this.loading = true;
    this.error = '';
    this.api.getShifts(this.page, this.perPage).subscribe({
      next: (res: any) => {
        this.shifts = res.data;
        this.total = res.total || (res.pagination?.total ?? 0);
        this.totalPages = Math.ceil(this.total / this.perPage) || 1;
        this.loading = false;
      },
      error: (err: any) => {
        this.error = 'Failed to load, please try again';
        this.loading = false;
      }
    });
  }

  loadCarers(): void {
    this.api.getCarers?.().subscribe({
      next: (res: any) => {
        this.carers = res.data || res;
      },
      error: () => {
        this.carers = [];
      }
    });
  }

  loadClients(): void {
    this.api.getClients?.().subscribe({
      next: (res: any) => {
        this.clients = res.data || res;
      },
      error: () => {
        this.clients = [];
      }
    });
  }

  goToPage(page: number): void {
    if (page < 1 || page > this.totalPages) return;
    this.page = page;
    this.loadShifts();
  }

  openAddModal(): void {
    this.editingShift = null;
    this.shiftForm.reset();
    this.formError = '';
    this.shiftModal?.show();
  }

  openEditModal(shift: Shift): void {
    this.editingShift = shift;
    this.shiftForm.patchValue({
      carer_id: shift.carer_id,
      client_id: shift.client_id,
      start_time: this.toInputDateTime(shift.start_time),
      end_time: this.toInputDateTime(shift.end_time),
      notes: shift.notes || ''
    });
    this.formError = '';
    this.shiftModal?.show();
  }

  closeModal(): void {
    this.shiftModal?.hide();
    this.editingShift = null;
    this.shiftForm.reset();
    this.formError = '';
  }

  saveShift(): void {
    if (this.shiftForm.invalid) {
      this.formError = 'Please fill in all required fields';
      return;
    }
    this.saving = true;
    const value = this.shiftForm.value;
    const payload = {
      carer_id: Number(value.carer_id),
      client_id: Number(value.client_id),
      start_time: this.toApiDateTime(value.start_time),
      end_time: this.toApiDateTime(value.end_time),
      notes: value.notes || ''
    };
    if (this.editingShift) {
      this.api.updateShift(this.editingShift.id, payload).subscribe({
        next: () => {
          this.saving = false;
          this.closeModal();
          this.loadShifts();
        },
        error: (err: any) => {
          this.formError = err?.error?.message || 'Save failed';
          this.saving = false;
        }
      });
    } else {
      this.api.createShift(payload).subscribe({
        next: () => {
          this.saving = false;
          this.closeModal();
          this.loadShifts();
        },
        error: (err: any) => {
          this.formError = err?.error?.message || 'Save failed';
          this.saving = false;
        }
      });
    }
  }

  openDeleteModal(shift: Shift): void {
    this.deleteTarget = shift;
    this.deleting = false;
    this.deleteModal?.show();
  }

  closeDeleteModal(): void {
    this.deleteModal?.hide();
    this.deleteTarget = null;
    this.deleting = false;
  }

  deleteShift(): void {
    if (!this.deleteTarget) return;
    this.deleting = true;
    this.api.deleteShift(this.deleteTarget.id).subscribe({
      next: () => {
        this.deleting = false;
        this.closeDeleteModal();
        this.loadShifts();
      },
      error: (err: any) => {
        this.deleting = false;
        this.error = 'Delete failed';
        this.closeDeleteModal();
      }
    });
  }

  // Utility: API datetime to input datetime
  toInputDateTime(dt: string): string {
    // 2025-06-27T12:00:00+00:00 => 2025-06-27T12:00
    return dt ? dt.replace(/\+.*$/, '').slice(0, 16) : '';
  }
  // Utility: input datetime to API datetime
  toApiDateTime(dt: string): string {
    // 2025-06-27T12:00 => 2025-06-27T12:00:00+00:00
    return dt ? dt + ':00+00:00' : '';
  }

  getCarerName(id: number): string {
    const carer = this.carers.find(c => c.id === id);
    return carer ? carer.name : id + '';
  }
  getCarerPhone(id: number): string {
    const carer = this.carers.find(c => c.id === id);
    return carer && carer.phone ? carer.phone : '-';
  }
  getClientName(id: number): string {
    const client = this.clients.find(c => c.id === id);
    return client ? client.name : id + '';
  }
  getClientPhone(id: number): string {
    const client = this.clients.find(c => c.id === id);
    return client && client.phone ? client.phone : '-';
  }
}