<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Auth;

readonly class LoginData
{
    public function __construct(
        public string $login, // Email or NIM/NIDN
        public string $password,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            login: trim((string) $data['login']),
            password: (string) $data['password'],
        );
    }
}
