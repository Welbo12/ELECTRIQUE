<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class KprimePayService
{
    private $merchantId;
    private $secret;
    private $baseUrl;
    private $gateway;

    public function __construct()
    {
        $this->merchantId = env('KPRIME_MERCHANT_ID');
        $this->secret = env('KPRIME_SECRET_KEY');
        $this->baseUrl = rtrim((string) env('KPRIME_BASE_URL'), '/');
        $this->gateway = env('KPRIME_GATEWAY', 'MIXX-YAS-TG');

        if (!$this->merchantId || !$this->secret || !$this->baseUrl) {
            throw new \RuntimeException('Configuration KPrimePay manquante (.env).');
        }
    }

    /**
     * Initie un push mobile money (USSD).
     */
    public function initierPaiement(array $data)
    {
        $required = [
            'transaction_id',
            'customer_name',
            'customer_email',
            'amount',
            'phone_number',
            'description',
        ];

        foreach ($required as $field) {
            if (!Arr::has($data, $field)) {
                throw new \InvalidArgumentException("Champ {$field} manquant pour l'appel KPrimePay.");
            }
        }

        $payload = [
            "merchant_number" => $this->merchantId,
            "transaction_id"  => $data['transaction_id'],
            "customer_name"   => $data['customer_name'],
            "customer_email"  => $data['customer_email'],
            "amount"          => (float) $data['amount'],
            "with_fees"       => (int) ($data['with_fees'] ?? 0),
            "gateway"         => $data['gateway'] ?? $this->gateway,
            "phone_number"    => $data['phone_number'],
            "description"     => $data['description'],
            "custom_meta_data"=> $data['custom_meta_data'] ?? [],
        ];

        $response = Http::withHeaders([
            "auth_token"   => $this->secret,
            "Content-Type" => "application/json",
        ])->post($this->baseUrl . "/mobilemoney/push-ussd", $payload);

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
            "auth_token" => $this->secret
        ])->get($this->baseUrl . "/transactions/" . $reference);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            "error" => true,
            "message" => $response->json('message') ?? 'Impossible de vérifier la transaction'
        ];
    }
}
