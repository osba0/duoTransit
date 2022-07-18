<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'clnmcl' => $this->faker->company,
            'cladcl' => $this->faker->address,
            'cltele' => $this->faker->phoneNumber,      
            'cletat' => 1
        ];
    }
}
