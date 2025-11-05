<?php

namespace App\Services\Subsystems;

class InventoryService
{
    private array $products = [
        ['id' => 1, 'name' => 'Laptop Dell XPS 15', 'description' => 'Laptop de alto rendimiento con procesador Intel Core i7, 16GB RAM, SSD 512GB', 'price' => 714995, 'category' => 'Laptops', 'stock' => 15],
        ['id' => 2, 'name' => 'iPhone 15 Pro', 'description' => 'Smartphone Apple con chip A17 Pro, cámara de 48MP, 256GB', 'price' => 549995, 'category' => 'Smartphones', 'stock' => 25],
        ['id' => 3, 'name' => 'Samsung Galaxy S24 Ultra', 'description' => 'Smartphone Samsung con pantalla AMOLED 6.8", cámara 200MP, 512GB', 'price' => 659995, 'category' => 'Smartphones', 'stock' => 20],
        ['id' => 4, 'name' => 'MacBook Pro M3', 'description' => 'Laptop Apple con chip M3, 16GB RAM unificada, SSD 1TB', 'price' => 1099995, 'category' => 'Laptops', 'stock' => 10],
        ['id' => 5, 'name' => 'iPad Air', 'description' => 'Tablet Apple con chip M1, pantalla Liquid Retina 10.9", 256GB', 'price' => 412495, 'category' => 'Tablets', 'stock' => 30],
        ['id' => 6, 'name' => 'AirPods Pro 2', 'description' => 'Auriculares inalámbricos con cancelación de ruido activa', 'price' => 137495, 'category' => 'Audio', 'stock' => 50],
        ['id' => 7, 'name' => 'Sony WH-1000XM5', 'description' => 'Audífonos over-ear con cancelación de ruido', 'price' => 219995, 'category' => 'Audio', 'stock' => 35],
        ['id' => 8, 'name' => 'Apple Watch Series 9', 'description' => 'Smartwatch con pantalla siempre activa, GPS', 'price' => 236495, 'category' => 'Wearables', 'stock' => 40],
        ['id' => 9, 'name' => 'PS5 Digital Edition', 'description' => 'Consola PlayStation 5 edición digital, 825GB SSD', 'price' => 247495, 'category' => 'Gaming', 'stock' => 12],
        ['id' => 10, 'name' => 'Nintendo Switch OLED', 'description' => 'Consola híbrida con pantalla OLED de 7", 64GB', 'price' => 192495, 'category' => 'Gaming', 'stock' => 28],
        ['id' => 11, 'name' => 'LG 55" OLED TV', 'description' => 'Smart TV OLED 4K, Dolby Vision IQ, 55 pulgadas', 'price' => 824995, 'category' => 'TV & Video', 'stock' => 8],
        ['id' => 12, 'name' => 'Canon EOS R6 Mark II', 'description' => 'Cámara mirrorless full-frame 24MP, 4K 60fps', 'price' => 1374995, 'category' => 'Cámaras', 'stock' => 6]
    ];

    public function initializeInventory(): void
    {
        if (!session()->has('inventory')) {
            session(['inventory' => $this->products]);
        }
    }

    public function getProducts(): array
    {
        $this->initializeInventory();
        return session('inventory', []);
    }

    public function getProduct(int $id): ?array
    {
        $products = $this->getProducts();
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }
        return null;
    }

    public function getAvailableProducts(): array
    {
        $products = $this->getProducts();
        return array_filter($products, fn($p) => $p['stock'] > 0);
    }

    public function checkStock(int $productId, int $quantity): bool
    {
        $product = $this->getProduct($productId);
        return $product && $product['stock'] >= $quantity;
    }

    public function updateStock(int $productId, int $quantity): void
    {
        $products = $this->getProducts();
        foreach ($products as &$product) {
            if ($product['id'] == $productId) {
                $product['stock'] -= $quantity;
                break;
            }
        }
        session(['inventory' => $products]);
    }
}