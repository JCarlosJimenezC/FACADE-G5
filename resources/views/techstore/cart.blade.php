<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .cart-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-cart3"></i> Mi Carrito</h2>
                <a href="{{ route('techstore.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Seguir Comprando
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(empty($cart) || count($cart) == 0)
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
                    <h3 class="mt-3">Tu carrito está vacío</h3>
                    <p class="text-muted">Agrega productos para comenzar tu compra</p>
                    <a href="{{ route('techstore.index') }}" class="btn btn-primary gradient-custom border-0 mt-3">
                        <i class="bi bi-shop"></i> Ver Productos
                    </a>
                </div>
            @else
                <form action="{{ route('techstore.cart.update') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $key => $item)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $item['name'] ?? 'Producto' }}</h6>
                                        </td>
                                        <td class="text-center">
                                            ${{ number_format($item['price'] ?? 0, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <input type="number" 
                                                   name="quantities[{{ $item['id'] ?? 0 }}]" 
                                                   value="{{ $item['quantity'] ?? 1 }}"
                                                   min="1"
                                                   max="{{ $item['max_stock'] ?? 99 }}"
                                                   class="form-control" 
                                                   style="width: 80px; margin: 0 auto;">
                                        </td>
                                        <td class="text-center">
                                            <strong>${{ number_format($item['subtotal'] ?? 0, 2) }}</strong>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('techstore.cart.remove', $item['id'] ?? 0) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('¿Eliminar este producto?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-secondary">
                                <i class="bi bi-arrow-repeat"></i> Actualizar Carrito
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4>Total: <span class="text-primary">${{ number_format($total ?? 0, 2) }}</span></h4>
                            <a href="{{ route('techstore.checkout') }}" class="btn btn-success btn-lg mt-2">
                                <i class="bi bi-credit-card"></i> Proceder al Pago
                            </a>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>