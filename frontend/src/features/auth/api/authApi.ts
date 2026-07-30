import api from '@/services/api';
import { AuthResponse, User } from '@/types/auth';

export const authApi = {
  async register(payload: {
    role_type: string;
    identity_number: string;
    name: string;
    email: string;
    phone?: string;
    password: string;
  }): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/register', payload);
    return response.data;
  },

  async login(payload: { login: string; password: string }): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/login', payload);
    return response.data;
  },

  async getMe(): Promise<{ success: boolean; data: User }> {
    const response = await api.get<{ success: boolean; data: User }>('/auth/me');
    return response.data;
  },

  async logout(): Promise<void> {
    await api.post('/auth/logout');
  },
};
