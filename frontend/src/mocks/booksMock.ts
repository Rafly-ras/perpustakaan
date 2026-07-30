export interface MockBook {
  id: number;
  isbn: string;
  title: string;
  author: string;
  publisher: string;
  publication_year: number;
  category: string;
  total_copies: number;
  available_copies: number;
  cover_url: string;
  status: 'available' | 'reserved_out_of_stock' | 'maintenance';
}

export const mockBooks: MockBook[] = [
  {
    id: 1,
    isbn: '978-602-03-3160-7',
    title: 'Clean Architecture: A Craftsman\'s Guide to Software Structure',
    author: 'Robert C. Martin (Uncle Bob)',
    publisher: 'Prentice Hall',
    publication_year: 2018,
    category: 'Software Engineering',
    total_copies: 5,
    available_copies: 3,
    cover_url: 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=400&q=80',
    status: 'available',
  },
  {
    id: 2,
    isbn: '978-0-13-235088-4',
    title: 'Clean Code: A Handbook of Agile Software Craftsmanship',
    author: 'Robert C. Martin',
    publisher: 'Prentice Hall',
    publication_year: 2008,
    category: 'Software Engineering',
    total_copies: 4,
    available_copies: 0,
    cover_url: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=400&q=80',
    status: 'reserved_out_of_stock',
  },
  {
    id: 3,
    isbn: '978-1-4919-5035-7',
    title: 'Designing Data-Intensive Applications',
    author: 'Martin Kleppmann',
    publisher: 'O\'Reilly Media',
    publication_year: 2017,
    category: 'Database & Infrastructure',
    total_copies: 6,
    available_copies: 4,
    cover_url: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80',
    status: 'available',
  },
  {
    id: 4,
    isbn: '978-0-201-63361-0',
    title: 'Design Patterns: Elements of Reusable Object-Oriented Software',
    author: 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
    publisher: 'Addison-Wesley',
    publication_year: 1994,
    category: 'Computer Science',
    total_copies: 3,
    available_copies: 1,
    cover_url: 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=400&q=80',
    status: 'available',
  },
  {
    id: 5,
    isbn: '978-1-61729-456-3',
    title: 'Spring in Action, Fifth Edition',
    author: 'Craig Walls',
    publisher: 'Manning Publications',
    publication_year: 2018,
    category: 'Backend Development',
    total_copies: 2,
    available_copies: 2,
    cover_url: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=400&q=80',
    status: 'available',
  },
];
