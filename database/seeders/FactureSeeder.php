<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facture;

class FactureSeeder extends Seeder
{
    public function run(): void
    {
        // Pour chaque utilisateur existant on crée entre 1 et 6 factures
        User::all()->each(function ($user) {
            $count = rand(2, 6);
            Facture::factory()->count($count)->create([
                'user_id' => $user->id,
            ])->each(function($facture) use ($user) {
                // Optionnel : si statut = payé, remplir paiement_date
                if ($facture->statut === 'payé') {
                    $facture->paiement_date = now()->subDays(rand(1, 90));
                    $facture->save();
                }
            });
        });
    }
}
