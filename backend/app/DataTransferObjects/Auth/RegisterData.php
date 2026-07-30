<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Auth;

readonly class RegisterData
{
    public function __construct(
        public string $role_type,       // Mahasiswa or Dosen
        public string $identity_number, // NIM or NIDN
        public string $name,
        public string $email,
        public ?string $phone,
        public string $password,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            role_type: (string) $data['role_type'],
            identity_number: trim((string) $data['identity_number']),
            name: trim((string) $data['name']),
            email: strtolower(trim((string) $data['email'])),
            phone: isset($data['phone']) ? trim((string) $data['phone']) : null,
            password: (string) $data['password'],
        );
    }
}
