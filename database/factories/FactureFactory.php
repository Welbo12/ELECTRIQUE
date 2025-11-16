<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FactureFactory extends Factory
{
    public function definition()
    {
        $year = $this->faker->numberBetween(2023, 2025);
        $month = $this->faker->numberBetween(1, 12);

        return [
            'reference' => 'FAC-' . strtoupper(Str::random(4)) . '-' . $year . sprintf('%02d', $month) . '-' . $this->faker->unique()->numberBetween(100,999),
             'client_name' => $this->faker->name(),
        'consommation' => $this->faker->numberBetween(50, 500),

            'montant' => $this->faker->randomFloat(2, 2000, 60000), // en XOF par ex.
            'mois' => $month,
            'annee' => $year,
            'date_limite' => $this->faker->dateTimeBetween("-60 days", "+30 days")->format('Y-m-d'),
            'statut' => $this->faker->randomElement(['non payé', 'payé']),
            'paiement_date' => null,
        ];
    }
}
