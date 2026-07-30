import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '@/services/api';
import type { User, LoginCredentials, RegisterPayload } from '@/types/auth';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('token'));
  const isLoading = ref<boolean>(false);
  const error = ref<string | null>(null);

  const isAuthenticated = computed(() => !!token.value && !!user.value);
  const userRole = computed(() => user.value?.role || null);
  const coinBalance = computed(() => user.value?.coin_balance ?? 0);
  const permissions = computed(() => user.value?.permissions || []);

  function setToken(newToken: string | null) {
    token.value = newToken;
    if (newToken) {
      localStorage.setItem('token', newToken);
      api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
    } else {
      localStorage.removeItem('token');
      delete api.defaults.headers.common['Authorization'];
    }
  }

  function getRedirectPathByRole(role?: string | null): string {
    switch (role || userRole.value) {
      case 'super_admin':
        return '/dashboard/super-admin';
      case 'admin':
        return '/dashboard/admin';
      case 'mahasiswa':
        return '/dashboard/student';
      case 'dosen':
        return '/dashboard/lecturer';
      default:
        return '/dashboard';
    }
  }

  function parseErrorMessage(err: any, fallbackMessage: string): string {
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors;
      const firstKey = Object.keys(errors)[0];
      if (firstKey && errors[firstKey]) {
        return Array.isArray(errors[firstKey]) ? errors[firstKey][0] : String(errors[firstKey]);
      }
    }
    return err.response?.data?.message || fallbackMessage;
  }

  async function login(credentials: LoginCredentials) {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await api.post('/auth/login', credentials);
      const { user: userData, token: authToken } = response.data.data;
      setToken(authToken);
      user.value = userData;
      return response.data;
    } catch (err: any) {
      error.value = parseErrorMessage(err, 'Login gagal. Periksa kembali kredensial Anda.');
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function register(payload: RegisterPayload) {
    isLoading.value = true;
    error.value = null;
    try {
      const response = await api.post('/auth/register', payload);
      const { user: userData, token: authToken } = response.data.data;
      setToken(authToken);
      user.value = userData;
      return response.data;
    } catch (err: any) {
      error.value = parseErrorMessage(err, 'Registrasi gagal. Periksa data inputan Anda.');
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchMe() {
    if (!token.value) return;
    try {
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
      const response = await api.get('/auth/me');
      user.value = response.data.data;
    } catch (err) {
      logout();
    }
  }

  function logout() {
    if (token.value) {
      api.post('/auth/logout').catch(() => {});
    }
    user.value = null;
    setToken(null);
  }

  function hasPermission(permissionName: string): boolean {
    if (userRole.value === 'super_admin') return true;
    return permissions.value.includes(permissionName) || permissions.value.includes('*');
  }

  return {
    user,
    token,
    isLoading,
    error,
    isAuthenticated,
    userRole,
    coinBalance,
    permissions,
    login,
    register,
    logout,
    fetchMe,
    getRedirectPathByRole,
    hasPermission,
  };
});
