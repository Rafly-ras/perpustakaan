# Library Management System (LMS)

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![Vue Version](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue.svg)](https://www.typescriptlang.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17-blue.svg)](https://www.postgresql.org)
[![Docker](https://img.shields.io/badge/Docker-Compose-blue.svg)](https://www.docker.com)

Aplikasi **Library Management System** berbasis web responsive (Mobile-Friendly) yang dibangun menggunakan arsitektur enterprise Decoupled (Backend Laravel 13 REST API + Frontend Vue 3 TypeScript SPA). Sistem dirancang sesuai dengan spesifikasi **FRD NCNG-FRD-001 v1.0**.

---

## 🚀 Fitur Utama Sistem

1. **Self-Registration Terintegrasi Master NIM/NIDN:** Validasi otomatis pendaftaran Mahasiswa & Dosen ke Master Akademik.
2. **Sistem Kuota Koin Bulanan:** Mahasiswa (5 Koin/bulan), Dosen (10 Koin/bulan). Reset massal otomatis via Task Scheduler setiap tanggal 1 awal bulan (`0 0 1 * *`).
3. **Reservasi Antrean FIFO:** Antrean otomatis untuk buku dengan stok 0 berbasis *First-In, First-Out*.
4. **WhatsApp Notification Gateway:** Pengingat otomatis jatuh tempo peminjaman & kabar ketersediaan buku antrean #1.
5. **Form Sirkulasi Meja Desk Fast Typing:** Pencarian peminjam & kode eksemplar real-time *Auto-Complete* tanpa hardware scanner.
6. **Generator Barcode & Cetak Label Stiker:** Auto-generate kode eksemplar unik (`LIB-YYYY-XXXX`) & print layout preview grid CSS.
7. **Unified Responsive Dashboard (RBAC):** Anturmuka seragam untuk 4 Role (Super Admin, Admin, Mahasiswa, Dosen).

---

## 📁 Struktur Folder Enterprise

```text
library-management-system/
├── backend/              # Laravel 13 (PHP 8.4 REST API - Action/Service/Repository)
├── frontend/             # Vue 3 (TypeScript + Vite + Pinia + Tailwind CSS)
├── docs/                 # Software Architecture Document (SAD)
│   └── SAD.md
├── docker-compose.yml    # Root Multi-Container Environment
└── README.md
```

---

## 🛠️ Cara Menjalankan Project

### Opsi A: Menggunakan Docker (Rekomendasi Utama)

Developer tidak perlu menginstal PHP, PostgreSQL, Redis, maupun Node.js secara manual di PC local.

1. **Clone repository & masuk ke direktori:**
   ```bash
   git clone https://github.com/your-org/library-management-system.git
   cd library-management-system
   ```

2. **Salin Environment Files:**
   ```bash
   cp backend/.env.example backend/.env
   cp frontend/.env.example frontend/.env
   ```

3. **Jalankan Docker Compose:**
   ```bash
   docker-compose up -d --build
   ```

4. **Akses Aplikasi:**
   - **Frontend App:** `http://localhost:5173`
   - **Backend REST API:** `http://localhost:8000/api/v1`
   - **PostgreSQL:** `localhost:5432` (User: `postgres`, Pass: `openpgpwd`, DB: `perpustakaan`)
   - **Redis:** `localhost:6379`

---

### Opsi B: Running Standalone (Tanpa Docker Container untuk App)

Jika ingin menjalankan Backend dan Frontend secara terpisah di terminal:

#### 1. Running Backend (Laravel 13)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
*Backend berjalan di: `http://localhost:8000`*

#### 2. Running Frontend (Vue 3 + Vite)
```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```
*Frontend berjalan di: `http://localhost:5173`*

---

## 👥 Pembagian Tim & Coding Convention

- **Backend Team (5 Dev):** Mengikuti standar **PSR-12** via `Laravel Pint` (`vendor/bin/pint`), Action Pattern (`app/Actions`), DTO (`app/DataTransferObjects`), dan Repository Pattern.
- **Frontend Team (3 Dev):** Mengikuti Vue 3 `<script setup lang="ts">`, ESLint + Prettier (`npm run lint`), Pinia Store, dan Feature-based Modules (`src/features/*`).
- **UI/UX (1 Designer):** Menggunakan Tailwind CSS Design Tokens & Layout Responsive Shell.
- **QA Team (2 QA):** Pengujian API via Postman/Swagger & End-to-End Test Matrix.

---

## 📄 Lisensi & Hak Cipta
Dokumen & Kode Sumber dikembangkan sesuai **FRD NCNG-FRD-001 v1.0** (2026).
