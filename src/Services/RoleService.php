<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Determine which roles the current user can edit.
     *
     * @return array
     */
    public function editableRoles(): array
    {
        // Get the current user's highest role (assuming only one main role per user)
        $currentUser = auth('web')->user();
        $currentRole = $currentUser->roles->first();

        // If no role is found, return an empty array
        if (!$currentRole) {
            return [];
        }

        // Fetch permissions associated with this role
        $currentRolePermissions = $currentRole->permissions->pluck('name')->toArray();
        // Fetch all roles
        $roles = Role::all();

        // Initialize the editable roles array
        $editableRoles = [];

        foreach ($roles as $role) {
            // Check if the current role has permission to edit this role
            $rolePermissionName = "edit_" . strtolower($role->name);

            // Check if the permission to edit this role exists in current user's role permissions
            $editableRoles[$role->name] = in_array($rolePermissionName, $currentRolePermissions) ? 1 : 0;
        }

        return $editableRoles;
    }

    public function getAllRoles(): array
    {
        return Cache::remember('roles.all', 3600, function () {
            return Role::orderBy('name')->pluck('name')->toArray();
        });
    }
}
