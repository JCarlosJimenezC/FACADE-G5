<?php

namespace App\Services\Subsystems;

class PaymentService
{
    public function processPayment(array $cardData, float $amount): array
    {
        return [
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => 'approved',
            'amount' => $amount,
            'card_last4' => substr($cardData['card_number'], -4),
            'processed_at' => now()->format('Y-m-d H:i:s')
        ];
    }

    public function validateCard(string $cardNumber, string $cvv, string $expiry): bool
    {
        return strlen($cardNumber) == 16 && strlen($cvv) == 3 && strlen($expiry) == 5;
    }

    public function maskCardNumber(string $cardNumber): string
    {
        return '****' . substr($cardNumber, -4);
    }
}