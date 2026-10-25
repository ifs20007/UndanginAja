<?php

namespace Database\Seeders;

use App\Enums\TemplateCategory;
use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Elegant Rose',
                'slug' => 'elegant-rose',
                'thumbnail' => '/images/templates/elegant-rose.jpg',
                'category' => TemplateCategory::Elegant,
                'is_premium' => false,
                'preview_url' => null,
            ],
            [
                'name' => 'Royal Gold',
                'slug' => 'royal-gold',
                'thumbnail' => '/images/templates/royal-gold.jpg',
                'category' => TemplateCategory::Elegant,
                'is_premium' => true,
                'preview_url' => null,
            ],
            [
                'name' => 'Garden Party',
                'slug' => 'garden-party',
                'thumbnail' => '/images/templates/garden-party.jpg',
                'category' => TemplateCategory::Rustic,
                'is_premium' => false,
                'preview_url' => null,
            ],
            [
                'name' => 'Vintage Rustic',
                'slug' => 'vintage-rustic',
                'thumbnail' => '/images/templates/vintage-rustic.jpg',
                'category' => TemplateCategory::Rustic,
                'is_premium' => true,
                'preview_url' => null,
            ],
            [
                'name' => 'Clean Minimalist',
                'slug' => 'clean-minimalist',
                'thumbnail' => '/images/templates/clean-minimalist.jpg',
                'category' => TemplateCategory::Minimalist,
                'is_premium' => false,
                'preview_url' => null,
            ],
            [
                'name' => 'Modern Luxury',
                'slug' => 'modern-luxury',
                'thumbnail' => '/images/templates/modern-luxury.jpg',
                'category' => TemplateCategory::Modern,
                'is_premium' => true,
                'preview_url' => null,
            ],
        ];

        foreach ($templates as $template) {
            Template::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
