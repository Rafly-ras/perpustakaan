<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterIdentity\StoreMasterIdentityRequest;
use App\Http\Requests\MasterIdentity\UpdateMasterIdentityRequest;
use App\Http\Resources\V1\MasterIdentityResource;
use App\Models\MasterIdentity;
use App\Services\MasterIdentityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterIdentityController extends Controller
{
    public function __construct(
        private readonly MasterIdentityService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'role_type', 'status']);
        $perPage = (int) $request->input('per_page', 15);
        $paginated = $this->service->getPaginated($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data master identitas berhasil dimuat.',
            'data' => MasterIdentityResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ], Response::HTTP_OK);
    }

    public function store(StoreMasterIdentityRequest $request): JsonResponse
    {
        $identity = $this->service->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data master identitas berhasil ditambahkan.',
            'data' => new MasterIdentityResource($identity),
        ], Response::HTTP_CREATED);
    }

    public function show(MasterIdentity $masterIdentity): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail master identitas dimuat.',
            'data' => new MasterIdentityResource($masterIdentity),
        ], Response::HTTP_OK);
    }

    public function update(UpdateMasterIdentityRequest $request, MasterIdentity $masterIdentity): JsonResponse
    {
        $updated = $this->service->update($masterIdentity, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data master identitas berhasil diperbarui.',
            'data' => new MasterIdentityResource($updated),
        ], Response::HTTP_OK);
    }

    public function destroy(MasterIdentity $masterIdentity): JsonResponse
    {
        $this->service->delete($masterIdentity);

        return response()->json([
            'success' => true,
            'message' => 'Data master identitas berhasil dihapus.',
            'data' => null,
        ], Response::HTTP_OK);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'role_type' => ['required', 'string', 'in:mahasiswa,dosen'],
            'file' => ['nullable', 'file', 'mimes:csv,txt,xlsx,xls'],
            'rows' => ['nullable', 'array'],
        ]);

        $roleType = $request->input('role_type', 'mahasiswa');
        $rows = [];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $handle = fopen($path, 'r');
            $header = fgetcsv($handle);

            if ($header) {
                // Normalize header columns
                $normalizedHeader = array_map(fn($col) => strtolower(trim(str_replace([' ', '/', '-'], '_', $col))), $header);

                while (($data = fgetcsv($handle)) !== false) {
                    if (count($data) === count($normalizedHeader)) {
                        $rows[] = array_combine($normalizedHeader, $data);
                    }
                }
            }
            fclose($handle);
        } elseif ($request->has('rows')) {
            $rows = $request->input('rows', []);
        }

        $summary = $this->service->import($rows, $roleType);

        return response()->json([
            'success' => true,
            'message' => "Proses import data master {$roleType} selesai.",
            'data' => $summary,
        ], Response::HTTP_OK);
    }

    public function export(Request $request): JsonResponse
    {
        $filters = $request->only(['role_type', 'status']);
        $records = $this->service->export($filters);

        return response()->json([
            'success' => true,
            'message' => 'Data master identitas berhasil diexport.',
            'data' => $records,
        ], Response::HTTP_OK);
    }
}
