<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1) ensure all permissions exist (Filament‑Shield auto‑generated)
        $allPermissions = Permission::pluck('name');

        // 2) admin → all permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($allPermissions);

        // 3) artist → only "view" permissions
        $artist = Role::firstOrCreate(['name' => 'artist']);
        $viewPerms = Permission::where('name', 'like', 'view %')
            ->orWhere('name', 'like', 'list %')
            ->pluck('name');
        $artist->syncPermissions($viewPerms);
    }
}
