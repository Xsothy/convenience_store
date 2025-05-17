<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure the artist role exists
        $artistRole = Role::firstOrCreate(['name' => 'artist']);

        // Define permissions for artists
        $artistPermissions = [
            // Product permissions - artists need to manage their own products
            'view_product',
            'view_any_product',
            'create_product',
            'update_product',
            'delete_product',

            // Category permissions - artists need to view categories for their products
            'view_category',
            'view_any_category',

            // Order permissions - artists need to view orders for their products
            'view_order',
            'view_any_order',
        ];

        // Sync permissions to the artist role
        $permissionsToAssign = Permission::whereIn('name', $artistPermissions)->get();
        $artistRole->syncPermissions($permissionsToAssign);

        // Create artist users from the provided list
        $artists = [
            [
                'name' => 'CHAN DEN',
                'email' => 'chanden@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'HEAN PHALLIN',
                'email' => 'heanphallin@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'THY CHANTHON',
                'email' => 'thychanthon@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'THACH MATU',
                'email' => 'thachmatu@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'REAN SOPHEA',
                'email' => 'reansophea@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'VAT CHANRA',
                'email' => 'vatchanra@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'HANG RATHANA',
                'email' => 'hangrathana@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'ROEUN SOPHEAK',
                'email' => 'roeunsopheak@artist.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'CHEA VOTHEA',
                'email' => 'cheavothea@artist.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($artists as $artistData) {
            // Create or update the artist user
            $artist = User::updateOrCreate(
                ['email' => $artistData['email']],
                $artistData
            );

            // Assign the artist role
            $artist->assignRole('artist');
        }
    }
}
