<?php

namespace App\Controllers;

use App\Enums\Role;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleController
{
    public function permissions(?Role $role): JsonResponse
    {
        if (! $role) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($role->permissions());
    }
}
