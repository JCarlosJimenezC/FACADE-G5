<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Subsystems\InventoryService;
use App\Services\Subsystems\PaymentService;
use App\Services\Subsystems\ShippingService;
use App\Services\Subsystems\OrderService;
use App\Services\CartService;
use App\Services\TechStoreFacade;

class TechStoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('inventory.service', function ($app) {
            return new InventoryService();
        });

        $this->app->singleton('payment.service', function ($app) {
            return new PaymentService();
        });

        $this->app->singleton('shipping.service', function ($app) {
            return new ShippingService();
        });

        $this->app->singleton('order.service', function ($app) {
            return new OrderService();
        });

        $this->app->singleton('cart.service', function ($app) {
            return new CartService();
        });

        $this->app->singleton('techstore.facade', function ($app) {
            return new TechStoreFacade();
        });
    }

    public function boot(): void
    {
        //
    }
}