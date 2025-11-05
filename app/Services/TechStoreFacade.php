<?php

namespace App\Services;

class TechStoreFacade
{
    public function getProducts(): array
    {
        return app('inventory.service')->getAvailableProducts();
    }

    public function getProduct(int $id): ?array
    {
        return app('inventory.service')->getProduct($id);
    }

    public function addToCart(int $productId, int $quantity): array
    {
        return app('cart.service')->addItem($productId, $quantity);
    }

    public function getCart(): array
    {
        return app('cart.service')->getCart();
    }

    public function getCartTotal(): float
    {
        return app('cart.service')->getTotal();
    }

    public function getCartCount(): int
    {
        return app('cart.service')->getItemCount();
    }

    public function updateCart(int $productId, int $quantity): void
    {
        app('cart.service')->updateQuantity($productId, $quantity);
    }

    public function removeFromCart(int $productId): void
    {
        app('cart.service')->removeItem($productId);
    }

    public function clearCart(): void
    {
        app('cart.service')->clearCart();
    }

    public function checkout(array $customerData, array $paymentData, string $shippingMethod = 'standard'): array
    {
        $cartService = app('cart.service');
        $inventoryService = app('inventory.service');
        $paymentService = app('payment.service');
        $shippingService = app('shipping.service');
        $orderService = app('order.service');
        
        $cart = $cartService->getCart();
        if (empty($cart)) {
            throw new \Exception("El carrito está vacío");
        }
        
        if (!$cartService->validateCart()) {
            throw new \Exception("Stock insuficiente para algunos productos");
        }
        
        if (!$paymentService->validateCard($paymentData['card_number'], $paymentData['card_cvv'], $paymentData['card_expiry'])) {
            throw new \Exception("Datos de tarjeta inválidos");
        }
        
        $subtotal = $cartService->getTotal();
        $shippingCost = $shippingService->calculateShipping($shippingMethod);
        $total = $subtotal + $shippingCost;
        
        $payment = $paymentService->processPayment($paymentData, $total);
        
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'product_id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal']
            ];
        }
        
        $orderData = [
            'customer' => $customerData,
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => [
                'method' => $shippingMethod,
                'cost' => $shippingCost,
                'estimated_delivery' => $shippingService->estimateDeliveryDate($shippingMethod),
                'tracking_number' => null
            ],
            'payment' => [
                'transaction_id' => $payment['transaction_id'],
                'card_last4' => $payment['card_last4'],
                'card_holder' => $paymentData['card_holder'],
                'amount' => $total,
                'status' => $payment['status']
            ],
            'total' => $total
        ];
        
        return $orderService->createOrder($orderData);
    }

    public function confirmPurchase(int $orderId): void
    {
        $orderService = app('order.service');
        $inventoryService = app('inventory.service');
        $shippingService = app('shipping.service');
        
        $order = $orderService->getOrder($orderId);
        
        if (!$order) {
            throw new \Exception("Orden no encontrada");
        }
        
        foreach ($order['items'] as $item) {
            $inventoryService->updateStock($item['product_id'], $item['quantity']);
        }
        
        $orders = session('orders', []);
        $orders[$orderId]['shipping']['tracking_number'] = $shippingService->generateTrackingNumber();
        session(['orders' => $orders]);
        
        $orderService->updateOrderStatus($orderId, 'confirmed');
        
        $this->clearCart();
    }

    public function getOrderDetails(int $orderId): ?array
    {
        return app('order.service')->getOrder($orderId);
    }

    public function getShippingMethods(): array
    {
        return app('shipping.service')->getShippingMethods();
    }
}