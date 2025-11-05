<?php

namespace App\Services;

class CartService
{
    public function addItem(int $productId, int $quantity): array
    {
        $inventoryService = app('inventory.service');
        $product = $inventoryService->getProduct($productId);
        
        if (!$product) {
            throw new \Exception("Producto no encontrado");
        }
        
        if (!$inventoryService->checkStock($productId, $quantity)) {
            throw new \Exception("Stock insuficiente. Disponible: " . $product['stock']);
        }
        
        $cart = session('cart', []);
        $key = 'product_' . $productId;
        
        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $quantity;
            if ($newQty > $product['stock']) {
                throw new \Exception("No puedes agregar más unidades. Stock máximo: " . $product['stock']);
            }
            $cart[$key]['quantity'] = $newQty;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $newQty;
        } else {
            $cart[$key] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity,
                'max_stock' => $product['stock']
            ];
        }
        
        session(['cart' => $cart]);
        return $cart[$key];
    }

    public function getCart(): array
    {
        return session('cart', []);
    }

    public function getTotal(): float
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'subtotal'));
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();
        unset($cart['product_' . $productId]);
        session(['cart' => $cart]);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCart();
        $key = 'product_' . $productId;
        
        if (isset($cart[$key]) && $quantity > 0) {
            $cart[$key]['quantity'] = $quantity;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $quantity;
            session(['cart' => $cart]);
        }
    }

    public function clearCart(): void
    {
        session()->forget('cart');
    }

    public function validateCart(): bool
    {
        $cart = $this->getCart();
        $inventoryService = app('inventory.service');
        
        foreach ($cart as $item) {
            if (!$inventoryService->checkStock($item['id'], $item['quantity'])) {
                return false;
            }
        }
        return true;
    }
}