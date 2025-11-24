<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KprimePayService
{
    private $merchantId;
    private $secret;
    private $baseUrl;

    public function __construct()
    {
        $this->merchantId = env('KPRIME_MERCHANT_ID');
        $this->secret = env('KPRIME_SECRET_KEY');
        $this->baseUrl = rtrim((string) env('KPRIME_BASE_URL'), '/');

        if (!$this->merchantId || !$this->secret || !$this->baseUrl) {
            throw new \RuntimeException('Configuration KPrimePay manquante (.env).');
        }
    }

    
    public function initierPaiement($montant, $reference, $description)
    {
        $payload = [
            "merchantId"   => $this->merchantId,
            "amount"       => (float)$montant,
            "reference"    => $reference,
            "description"  => $description,
            "currency"     => "XOF",
            "callbackUrl"  => route('paiement.callback'),
            "returnUrl"    => url('/dashboard')
        ];

        
        $signature = hash_hmac('sha256', json_encode($payload), $this->secret);

        $response = Http::withHeaders([
            "x-api-key"  => $this->secret,
            "Signature"  => $signature,
            "Content-Type" => "application/json"
        ])->post($this->baseUrl . "/payment/init", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            "error" => true,
            "message" => $response->json('message') ?? 'API KPrimePay non disponible'
        ];
    }


   
    public function verifierPaiement($reference)
    {
        $response = Http::withHeaders([
            "x-api-key" => $this->secret
        ])->get($this->baseUrl . "/payment/status/" . $reference);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            "error" => true,
            "message" => $response->json('message') ?? 'Impossible de vérifier la transaction'
        ];
    }
}
