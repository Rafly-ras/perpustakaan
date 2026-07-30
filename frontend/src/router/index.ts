import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/features/auth/stores/useAuthStore';
import HomeView from '../views/HomeView.vue';
import LoginPage from '@/features/auth/pages/LoginPage.vue';
import RegisterPage from '@/features/auth/pages/RegisterPage.vue';
import ForgotPasswordPage from '@/features/auth/pages/ForgotPasswordPage.vue';

import SuperAdminDashboard from '@/views/dashboards/SuperAdminDashboard.vue';
import AdminDashboard from '@/views/dashboards/AdminDashboard.vue';
import StudentDashboard from '@/views/dashboards/StudentDashboard.vue';
import LecturerDashboard from '@/views/dashboards/LecturerDashboard.vue';

import MasterIdentityPage from '@/views/admin/MasterIdentityPage.vue';
import UserManagementPage from '@/views/admin/UserManagementPage.vue';

import OpacPage from '@/views/opac/OpacPage.vue';
import ReservationsPage from '@/views/reservations/ReservationsPage.vue';
import BooksManagementPage from '@/views/admin/BooksManagementPage.vue';
import BarcodeGeneratorPage from '@/views/admin/BarcodeGeneratorPage.vue';
import CirculationDeskPage from '@/views/admin/CirculationDeskPage.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'login',
    component: LoginPage,
    meta: { guestOnly: true },
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterPage,
    meta: { guestOnly: true },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPasswordPage,
    meta: { guestOnly: true },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: HomeView,
    meta: { requiresAuth: true },
  },
  {
    path: '/dashboard/super-admin',
    name: 'dashboard-super-admin',
    component: SuperAdminDashboard,
    meta: { requiresAuth: true, roles: ['super_admin'] },
  },
  {
    path: '/dashboard/admin',
    name: 'dashboard-admin',
    component: AdminDashboard,
    meta: { requiresAuth: true, roles: ['admin', 'super_admin'] },
  },
  {
    path: '/dashboard/student',
    name: 'dashboard-student',
    component: StudentDashboard,
    meta: { requiresAuth: true, roles: ['mahasiswa', 'super_admin'] },
  },
  {
    path: '/dashboard/lecturer',
    name: 'dashboard-lecturer',
    component: LecturerDashboard,
    meta: { requiresAuth: true, roles: ['dosen', 'super_admin'] },
  },
  {
    path: '/opac',
    name: 'opac',
    component: OpacPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/reservations',
    name: 'reservations',
    component: ReservationsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/books',
    name: 'admin-books',
    component: BooksManagementPage,
    meta: { requiresAuth: true, roles: ['admin', 'super_admin'] },
  },
  {
    path: '/admin/barcodes',
    name: 'admin-barcodes',
    component: BarcodeGeneratorPage,
    meta: { requiresAuth: true, roles: ['admin', 'super_admin'] },
  },
  {
    path: '/admin/circulation',
    name: 'admin-circulation',
    component: CirculationDeskPage,
    meta: { requiresAuth: true, roles: ['admin', 'super_admin'] },
  },
  {
    path: '/admin/master-identities',
    name: 'master-identities',
    component: MasterIdentityPage,
    meta: { requiresAuth: true, roles: ['super_admin'] },
  },
  {
    path: '/admin/users',
    name: 'user-management',
    component: UserManagementPage,
    meta: { requiresAuth: true, roles: ['super_admin'] },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Fetch current user if token exists but user state is null
  if (authStore.token && !authStore.user) {
    await authStore.fetchMe();
  }

  const isAuthenticated = authStore.isAuthenticated;
  const userRole = authStore.userRole;

  // 1. Guest Only Routes Guard
  if (to.meta.guestOnly && isAuthenticated) {
    return next(authStore.getRedirectPathByRole(userRole));
  }

  // 2. Protected Routes Guard
  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } });
  }

  // 3. Role-based Access Guard (RBAC)
  if (to.meta.roles && Array.isArray(to.meta.roles)) {
    const allowedRoles = to.meta.roles as string[];
    if (userRole && !allowedRoles.includes(userRole) && userRole !== 'super_admin') {
      return next(authStore.getRedirectPathByRole(userRole));
    }
  }

  next();
});

export default router;
