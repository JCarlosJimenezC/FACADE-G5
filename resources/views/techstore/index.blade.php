<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Tienda de Tecnología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .header-store {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .product-card {
            transition: transform 0.3s;
            height: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .cart-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header-store">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-4 mb-0"><i class="bi bi-shop"></i> TechStore</h1>
                    <p class="lead mb-0">Encuentra la mejor tecnología al mejor precio</p>
                </div>
                <div>
                    <a href="{{ route('techstore.cart') }}" class="btn btn-primary gradient-custom border-0 position-relative btn-lg">
                        <i class="bi bi-cart3"></i> Carrito
                        @if(App\Facades\TechStore::getCartCount() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                {{ App\Facades\TechStore::getCartCount() }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Productos -->
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-4">
                <div class="card product-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="bi bi-laptop" style="font-size: 4rem; color: #667eea;"></i>
                        </div>
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text text-muted small">{{ $product['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">{{ $product['category'] }}</span>
                            <span class="badge bg-success">Stock: {{ $product['stock'] }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-primary mb-0">${{ number_format($product['price'], 2) }}</h4>
                        </div>
                        <form action="{{ route('techstore.cart.add') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                            <div class="input-group">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product['stock'] }}" 
                                       class="form-control" style="max-width: 80px;">
                                <button type="submit" class="btn btn-primary gradient-custom border-0 flex-grow-1">
                                    <i class="bi bi-cart-plus"></i> Agregar al Carrito
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No hay productos disponibles en este momento.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>