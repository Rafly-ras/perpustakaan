export type Role = 'super_admin' | 'admin' | 'mahasiswa' | 'dosen';

export interface User {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  role: Role;
  nim: string | null;
  nidn: string | null;
  coin_balance: number;
  permissions: string[];
  created_at?: string;
}

export interface LoginCredentials {
  login: string;
  password: string;
  remember?: boolean;
}

export interface RegisterPayload {
  name: string;
  email: string;
  phone: string;
  password: string;
  password_confirmation: string;
  role: 'mahasiswa' | 'dosen';
  nim?: string;
  nidn?: string;
}

export interface AuthResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
}
