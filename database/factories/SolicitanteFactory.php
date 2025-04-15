<?php

namespace Database\Factories;

use App\Models\Solicitante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SolicitanteFactory extends Factory
{   

    protected $model = Solicitante::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_solicitante' => $this->faker->sentence(),
            'plaza' => $this->faker->sentence(),
            'gafete' => $this->faker->sentence(),
            'agencia_mp' => $this->faker->sentence(),
            'turno' => $this->faker->sentence(),
        ];
    }
}
