<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Folio>
 */
class FolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_solicitante' => $this->faker->randomElement([1, 2, 3, 4, 5,]),
            'ticket' => $this->faker->sentence(),
            'acronimo' => $this->faker->sentence(),
            'hora' => 1234,
            'dia_mes' => 5678,
            'anio' => 2025,
            'folio' => $this->faker->sentence(),
            'razon' => $this->faker->paragraph(),
            'numero_registro' => $this->faker->sentence(),
            'id_delito' => $this->faker->randomElement([1, 2, 3,]),
            'detenido' => $this->faker->sentence(),
            'id_user' => $this->faker->randomElement([1, 2,]),
        ];
    }
}
