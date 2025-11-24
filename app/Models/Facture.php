<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'transaction_reference',
        'montant',
        'mois',
        'annee',
        'date_limite',
        'statut',
        'paiement_date',
    ];

    // Relation vers l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Petit accessor utile pour afficher le mois en texte si besoin
    public function getMoisTexteAttribute()
    {
        return \DateTime::createFromFormat('!m', $this->mois)->format('F'); // "January" etc. -> tu peux traduire
    }
}
