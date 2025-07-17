<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $continents = ['Europa', 'Asia', 'Africa', 'Nord America', 'Sud America', 'Oceania'];
        
        return [
            'name' => $this->faker->country(),
            'code' => strtoupper($this->faker->unique()->lexify('??')),
            'flag_url' => $this->faker->imageUrl(320, 200, 'flags'),
            'capital' => $this->faker->city(),
            'continent' => $this->faker->randomElement($continents),
            'difficulty_level' => $this->faker->numberBetween(1, 5),
            'is_active' => $this->faker->boolean(90) // 90% di probabilità di essere attivo
        ];
    }
}
