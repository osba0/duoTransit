<?php

namespace Database\Factories;

use App\Models\Dry;
use Illuminate\Database\Eloquent\Factories\Factory;

class DryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'drynom' => $this->faker->name,
            'drydes' => $this->faker->paragraph,
            'dryeta' => 1
        ];
    }
}
