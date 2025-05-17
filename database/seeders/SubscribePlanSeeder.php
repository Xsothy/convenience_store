<?php

namespace Database\Seeders;

use App\Models\SubscribePlan;
use Illuminate\Database\Seeder;

class SubscribePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subscription plans
        SubscribePlan::truncate();

        // Create Bronze plan
        SubscribePlan::create([
            'name' => 'Bronze',
            'price' => 10,
            'annual_price' => 80,
            'currency' => 'USD',
            'is_active' => true,
            'is_popular' => false,
            'description' => 'Perfect for casual art lovers who want to enjoy digital downloads and small discounts on custom work.',
            'features_list' => [
                'Any 3 digital download (any art print, digital art, wedding template, map template, etc.)',
                '5% discount on original art and custom works',
                'Access to member-only content',
                'Early access to new releases',
            ],
        ]);

        // Create Silver plan
        SubscribePlan::create([
            'name' => 'Silver',
            'price' => 30,
            'annual_price' => 300,
            'currency' => 'USD',
            'is_active' => true,
            'is_popular' => true,
            'description' => 'Ideal for art enthusiasts who want both digital and physical artwork with better discounts.',
            'features_list' => [
                'Any 3 digital art downloads',
                '1 rolled Canvas print (up to 19" x 27"), shipped free',
                'Upload your photo for custom canvas',
                '10% discount on original art and custom work',
                'Priority customer support',
                'Access to member-only collections',
            ],
        ]);

        // Create Gold plan
        SubscribePlan::create([
            'name' => 'Gold',
            'price' => 50,
            'annual_price' => 530,
            'currency' => 'USD',
            'is_active' => true,
            'is_popular' => false,
            'description' => 'Our premium tier for serious art collectors who want maximum benefits and exclusive discounts.',
            'features_list' => [
                'Any 3 digital art downloads',
                '1 rolled Canvas print (up to 19" x 27"), shipped free',
                'Upload your photo for custom canvas',
                '15% discount on original art work, custom work, and wedding packages',
                'VIP customer support',
                'Early access to limited edition pieces',
                'Exclusive artist meet and greets',
                'Monthly art consultation calls',
            ],
        ]);
    }
}
