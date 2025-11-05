<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\TechStore;

class TechStoreController extends Controller
{
    public function index()
    {
        $products = TechStore::getProducts();
        return view('techstore.index', compact('products'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            TechStore::addToCart($validated['product_id'], $validated['quantity']);
            return redirect()->back()->with('success', '✅ Producto agregado al carrito');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ ' . $e->getMessage());
        }
    }

    public function cart()
    {
        $cart = TechStore::getCart();
        $total = TechStore::getCartTotal();
        $count = TechStore::getCartCount();
        
        return view('techstore.cart', compact('cart', 'total', 'count'));
    }

    public function updateCart(Request $request)
    {
        if ($request->has('quantities')) {
            foreach ($request->quantities as $productId => $quantity) {
                if ($quantity > 0) {
                    TechStore::updateCart($productId, $quantity);
                }
            }
        }
        
        return redirect()->route('techstore.cart')->with('success', '✅ Carrito actualizado');
    }

    public function removeFromCart($productId)
    {
        TechStore::removeFromCart($productId);
        return redirect()->route('techstore.cart')->with('success', '✅ Producto eliminado');
    }

    public function checkout()
    {
        $cart = TechStore::getCart();
        
        if (empty($cart)) {
            return redirect()->route('techstore.cart')->with('error', '❌ Tu carrito está vacío');
        }
        
        $total = TechStore::getCartTotal();
        $shippingMethods = TechStore::getShippingMethods();
        
        return view('techstore.checkout', compact('cart', 'total', 'shippingMethods'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'shipping_method' => 'required|in:standard,express',
            'card_number' => 'required|string|size:16',
            'card_holder' => 'required|string|max:255',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3'
        ]);

        try {
            $customerData = [
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
                'address' => $validated['customer_address']
            ];

            $paymentData = [
                'card_number' => $validated['card_number'],
                'card_holder' => $validated['card_holder'],
                'card_expiry' => $validated['card_expiry'],
                'card_cvv' => $validated['card_cvv']
            ];

            $order = TechStore::checkout($customerData, $paymentData, $validated['shipping_method']);
            
            return redirect()->route('techstore.confirm', $order['id']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', '❌ ' . $e->getMessage());
        }
    }

    public function confirm($orderId)
    {
        $order = TechStore::getOrderDetails($orderId);
        
        if (!$order) {
            return redirect()->route('techstore.index')->with('error', '❌ Orden no encontrada');
        }
        
        return view('techstore.confirm', compact('order'));
    }

    public function confirmOrder($orderId)
    {
        try {
            TechStore::confirmPurchase($orderId);
            return redirect()->route('techstore.success', $orderId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = TechStore::getOrderDetails($orderId);
        
        if (!$order) {
            return redirect()->route('techstore.index')->with('error', '❌ Orden no encontrada');
        }
        
        return view('techstore.success', compact('order'));
    }
}