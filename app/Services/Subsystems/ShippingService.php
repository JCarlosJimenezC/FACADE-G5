<?php

namespace App\Services\Subsystems;

class ShippingService
{
    public function calculateShipping(string $method = 'standard'): float
    {
        return match($method) {
            'express' => 25.00,
            'standard' => 10.00,
            default => 10.00
        };
    }

    public function estimateDeliveryDate(string $method = 'standard'): string
    {
        $days = match($method) {
            'express' => 2,
            'standard' => 5,
            default => 5
        };
        return now()->addDays($days)->format('Y-m-d');
    }

    public function getShippingMethods(): array
    {
        return [
            'standard' => ['name' => 'Envío Estándar', 'cost' => 10.00, 'days' => '5-7 días'],
            'express' => ['name' => 'Envío Express', 'cost' => 25.00, 'days' => '2-3 días']
        ];
    }

    public function generateTrackingNumber(): string
    {
        return 'TRACK-' . strtoupper(substr(md5(uniqid()), 0, 10));
    }
}