export interface MockReservation {
  id: number;
  queue_number: number;
  book_title: string;
  book_isbn: string;
  user_name: string;
  user_nim_nidn: string;
  user_role: string;
  reserved_at: string;
  status: 'waiting' | 'ready_for_pickup' | 'completed' | 'cancelled';
}

export const mockReservations: MockReservation[] = [
  {
    id: 1,
    queue_number: 1,
    book_title: 'Clean Code: A Handbook of Agile Software Craftsmanship',
    book_isbn: '978-0-13-235088-4',
    user_name: 'Budi Santoso',
    user_nim_nidn: '20261001',
    user_role: 'Mahasiswa',
    reserved_at: '2026-07-28 09:15:00',
    status: 'waiting',
  },
  {
    id: 2,
    queue_number: 2,
    book_title: 'Clean Code: A Handbook of Agile Software Craftsmanship',
    book_isbn: '978-0-13-235088-4',
    user_name: 'Siti Rahmawati',
    user_nim_nidn: '20261002',
    user_role: 'Mahasiswa',
    reserved_at: '2026-07-28 10:45:00',
    status: 'waiting',
  },
  {
    id: 3,
    queue_number: 1,
    book_title: 'Designing Data-Intensive Applications',
    book_isbn: '978-1-4919-5035-7',
    user_name: 'Dr. Chairul Umam, M.Kom',
    user_nim_nidn: '198501012010',
    user_role: 'Dosen',
    reserved_at: '2026-07-28 11:20:00',
    status: 'ready_for_pickup',
  },
];
