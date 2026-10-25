<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'features' => [
                    '1 Undangan Aktif',
                    'Template Dasar',
                    'RSVP Teks Standar (Hadir/Tidak)',
                    'Galeri Foto Terbatas (3 Foto)',
                    'Countdown Timer Standar',
                    'Share WhatsApp Manual',
                ],
                'max_invitations' => 1,
                'wa_blast_quota' => 0,
                'is_popular' => false,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 99000,
                'features' => [
                    'Kuota 5 Undangan',
                    'Custom Domain',
                    'Seluruh Pustaka Template',
                    'Amplop Digital & Rekening',
                    'Custom Background Music (MP3 URL)',
                    'Live Streaming Embed (YouTube/IG)',
                    'Petunjuk Busana (Dresscode)',
                    'Kustomisasi Font',
                    'Ekspor Data Tamu (CSV)',
                    'Analytics Pengunjung',
                ],
                'max_invitations' => 5,
                'wa_blast_quota' => 0,
                'is_popular' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'price' => 249000,
                'features' => [
                    'Kuota Unlimited Undangan',
                    'White Label (Tanpa Watermark)',
                    'Love Story Timeline',
                    'Voice Comment (RSVP Audio)',
                    'Guest on Screen (Layar Check-in Hari H)',
                    'Akses Modul Auto Blast WhatsApp',
                    'Semua Fitur Paket Pro',
                    'Priority Support 24/7',
                ],
                'max_invitations' => 999,
                'wa_blast_quota' => 500,
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
