<?php

namespace App\Services\Subsystems;

class OrderService
{
    public function initializeOrders(): void
    {
        if (!session()->has('orders')) {
            session(['orders' => []]);
        }
        if (!session()->has('order_counter')) {
            session(['order_counter' => 1]);
        }
    }

    public function createOrder(array $orderData): array
    {
        $this->initializeOrders();
        
        $orderId = session('order_counter');
        $orderData['id'] = $orderId;
        $orderData['status'] = 'pending';
        $orderData['created_at'] = now()->format('Y-m-d H:i:s');
        
        $orders = session('orders', []);
        $orders[$orderId] = $orderData;
        
        session(['orders' => $orders]);
        session(['order_counter' => $orderId + 1]);
        
        return $orderData;
    }

    public function getOrder(int $orderId): ?array
    {
        $orders = session('orders', []);
        return $orders[$orderId] ?? null;
    }

    public function updateOrderStatus(int $orderId, string $status): void
    {
        $orders = session('orders', []);
        if (isset($orders[$orderId])) {
            $orders[$orderId]['status'] = $status;
            session(['orders' => $orders]);
        }
    }
}