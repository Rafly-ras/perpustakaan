<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'role', 'status']);
        $perPage = (int) $request->input('per_page', 15);
        $paginated = $this->userService->getPaginated($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil dimuat.',
            'data' => UserResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ], Response::HTTP_OK);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil ditambahkan.',
            'data' => new UserResource($user),
        ], Response::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail profil pengguna dimuat.',
            'data' => new UserResource($user->load('roleModel.permissions', 'masterIdentity')),
        ], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updated = $this->userService->update($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui.',
            'data' => new UserResource($updated->load('roleModel.permissions', 'masterIdentity')),
        ], Response::HTTP_OK);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil dihapus dari sistem.',
            'data' => null,
        ], Response::HTTP_OK);
    }

    public function resetPassword(ResetPasswordRequest $request, User $user): JsonResponse
    {
        $updated = $this->userService->resetPassword($user, $request->input('password'));

        return response()->json([
            'success' => true,
            'message' => 'Password pengguna berhasil direset.',
            'data' => new UserResource($updated),
        ], Response::HTTP_OK);
    }

    public function activate(User $user): JsonResponse
    {
        $updated = $this->userService->activate($user);

        return response()->json([
            'success' => true,
            'message' => 'Status akun pengguna berhasil diaktifkan.',
            'data' => new UserResource($updated),
        ], Response::HTTP_OK);
    }

    public function deactivate(User $user): JsonResponse
    {
        $updated = $this->userService->deactivate($user);

        return response()->json([
            'success' => true,
            'message' => 'Status akun pengguna berhasil dinonaktifkan.',
            'data' => new UserResource($updated),
        ], Response::HTTP_OK);
    }
}
