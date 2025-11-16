<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
              $table->string('client_name');
            $table->decimal('montant', 10, 2);
            $table->tinyInteger('mois'); // 1..12
            $table->smallInteger('annee'); // ex: 2025
              $table->integer('consommation')->nullable();
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['non payé','payé','annulé'])->default('non payé');
            $table->timestamp('paiement_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
