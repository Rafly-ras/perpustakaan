<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MasterIdentity;
use App\Repositories\Contracts\MasterIdentityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MasterIdentityService
{
    public function __construct(
        private readonly MasterIdentityRepositoryInterface $repository
    ) {}

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters, $perPage);
    }

    public function create(array $data): MasterIdentity
    {
        return $this->repository->create($data);
    }

    public function update(MasterIdentity $identity, array $data): MasterIdentity
    {
        return $this->repository->update($identity, $data);
    }

    public function delete(MasterIdentity $identity): bool
    {
        return $this->repository->delete($identity);
    }

    public function import(array $rows, string $roleType = 'mahasiswa'): array
    {
        return DB::transaction(function () use ($rows, $roleType) {
            $inserted = 0;
            $updated = 0;
            $skipped = 0;
            $failed = 0;

            foreach ($rows as $row) {
                try {
                    $identityNumber = trim((string) ($row['identity_number'] ?? $row['nim'] ?? $row['nidn'] ?? ''));
                    $fullName = trim((string) ($row['full_name'] ?? $row['nama'] ?? ''));
                    $email = trim((string) ($row['email'] ?? '')) ?: null;
                    $phone = trim((string) ($row['phone'] ?? $row['whatsapp'] ?? '')) ?: null;

                    if (empty($identityNumber) || empty($fullName)) {
                        $failed++;
                        continue;
                    }

                    $existing = $this->repository->findByIdentityNumber($identityNumber);

                    if ($existing) {
                        $skipped++;
                        continue;
                    }

                    $this->repository->create([
                        'identity_number' => $identityNumber,
                        'full_name' => $fullName,
                        'email' => $email,
                        'phone' => $phone,
                        'role_type' => strtolower($roleType),
                        'status' => 'active',
                    ]);

                    $inserted++;
                } catch (\Throwable $e) {
                    $failed++;
                }
            }

            return [
                'inserted' => $inserted,
                'updated' => $updated,
                'skipped' => $skipped,
                'failed' => $failed,
                'total_processed' => count($rows),
            ];
        });
    }

    public function export(array $filters = []): array
    {
        $records = MasterIdentity::query()
            ->when(! empty($filters['role_type']), fn($q) => $q->where('role_type', $filters['role_type']))
            ->when(! empty($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->get();

        return $records->map(fn($item) => [
            'NIM/NIDN' => $item->identity_number,
            'Nama Lengkap' => $item->full_name,
            'Email' => $item->email ?? '',
            'No WhatsApp' => $item->phone ?? '',
            'Peran' => ucfirst($item->role_type),
            'Status' => ucfirst($item->status),
            'Tanggal Dibuat' => $item->created_at?->format('Y-m-d H:i:s'),
        ])->toArray();
    }
}
