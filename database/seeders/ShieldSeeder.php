<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate all shield resources
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);

        $this->command->info('Shield resources generated.');

        // Create admin role with all permissions (instead of super_admin)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Get all permissions and assign them to admin
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        $this->command->info('Admin role assigned all permissions.');
    }
}
