<?php

namespace App\Services;


class PermissionService {

    public function CheckPermissions($user): array {

        return match($user->role){

            'superadmin' => ['*'], // Wildcard for all permissions
            'admin'     => ['users:read', 'posts:manage', 'wallet:manage'],
            'editor'    => ['posts:create', 'posts:edit'],
            default     => ['posts:read']
        };
    }


}
