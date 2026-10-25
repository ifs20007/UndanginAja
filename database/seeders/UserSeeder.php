<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Hak Akses Tertinggi (Paket Business - Unlimited Access)
        User::updateOrCreate(
            ['email' => 'admin@undanginaja.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'subscription_plan' => 'business',
                'subscription_expires_at' => now()->addYears(10),
                'wa_blast_quota' => 500,
            ]
        );

        // 2. Akun Demo Tier Pro
        User::updateOrCreate(
            ['email' => 'pro@undanginaja.com'],
            [
                'name' => 'Pengantin Pro Demo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'subscription_plan' => 'pro',
                'subscription_expires_at' => now()->addMonths(6),
                'wa_blast_quota' => 0,
            ]
        );

        // 3. Akun Demo Tier Free
        User::updateOrCreate(
            ['email' => 'free@undanginaja.com'],
            [
                'name' => 'Pengantin Free Demo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'subscription_plan' => 'free',
                'subscription_expires_at' => null,
                'wa_blast_quota' => 0,
            ]
        );
    }
}
