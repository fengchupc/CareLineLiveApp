export interface Shift {
  id: number;
  carer_id: number;
  client_id: number;
  carer_name?: string;
  client_name?: string;
  date: string;
  start_time: string;
  end_time: string;
  notes?: string;
  created_at: string;
  updated_at: string;
  carer?: {
    id: number;
    name: string;
  };
  client?: {
    id: number;
    name: string;
  };
}

export interface ShiftResponse {
  data: Shift[];
  total: number;
  pagination: {
    current_page: number;
    last_page: number;
    per_page: number;
  };
} 