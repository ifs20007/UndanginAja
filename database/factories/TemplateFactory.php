<?php

namespace Database\Factories;

use App\Enums\TemplateCategory;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'thumbnail' => 'templates/thumbnails/'.Str::slug($name).'.jpg',
            'category' => fake()->randomElement(TemplateCategory::cases()),
            'is_premium' => fake()->boolean(30),
            'preview_url' => fake()->url(),
        ];
    }
}
