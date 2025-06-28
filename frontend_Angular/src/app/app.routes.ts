import { Routes } from '@angular/router';
import { ShiftsComponent } from './shifts/shifts.component';

export const routes: Routes = [
  { path: '', component: ShiftsComponent },
  { path: '**', redirectTo: '' }
]; 