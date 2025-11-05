<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .checkout-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .section-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .credit-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-container">
                    <h2 class="text-center mb-4"><i class="bi bi-credit-card"></i> Finalizar Compra</h2>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Resumen del Carrito -->
                    <div class="section-box">
                        <h5><i class="bi bi-cart-check"></i> Resumen del Carrito</h5>
                        <hr>
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>₡{{ number_format($item['subtotal'], 0) }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Subtotal:</strong>
                            <strong>₡{{ number_format($total, 0) }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('techstore.checkout.process') }}" method="POST">
                        @csrf

                        <!-- Información del Cliente -->
                        <h5 class="mt-4 mb-3"><i class="bi bi-person-circle"></i> Información del Cliente</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       name="customer_email" value="{{ old('customer_email') }}" required>
                                @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       name="customer_phone" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección de Envío *</label>
                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                      name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                            @error('customer_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Método de Envío -->
                        <h5 class="mt-4 mb-3"><i class="bi bi-truck"></i> Método de Envío</h5>
                        
                        @foreach($shippingMethods as $key => $method)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="shipping_method" 
                                       id="shipping_{{ $key }}" value="{{ $key }}" 
                                       {{ $key == 'standard' ? 'checked' : '' }}
                                       onchange="updateTotal(this.value)">
                                <label class="form-check-label" for="shipping_{{ $key }}">
                                    <strong>{{ $method['name'] }}</strong> - ₡{{ number_format($method['cost'], 0) }}
                                    <small class="text-muted">({{ $method['days'] }})</small>
                                </label>
                            </div>
                        @endforeach

                        <!-- Información de Pago -->
                        <h5 class="mt-4 mb-3"><i class="bi bi-credit-card-2-front"></i> Información de Pago</h5>
                        
                        <div class="credit-card">
                            <div class="mb-3">
                                <label class="form-label">Número de Tarjeta *</label>
                                <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                       name="card_number" id="card_number" placeholder="1234 5678 9012 3456" 
                                       maxlength="16" value="{{ old('card_number') }}" required>
                                @error('card_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre del Titular *</label>
                                <input type="text" class="form-control @error('card_holder') is-invalid @enderror" 
                                       name="card_holder" placeholder="NOMBRE APELLIDO" 
                                       value="{{ old('card_holder') }}" required>
                                @error('card_holder')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Vencimiento *</label>
                                    <input type="text" class="form-control @error('card_expiry') is-invalid @enderror" 
                                           name="card_expiry" id="card_expiry" placeholder="MM/YY" 
                                           maxlength="5" value="{{ old('card_expiry') }}" required>
                                    @error('card_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">CVV *</label>
                                    <input type="text" class="form-control @error('card_cvv') is-invalid @enderror" 
                                           name="card_cvv" id="card_cvv" placeholder="123" 
                                           maxlength="3" value="{{ old('card_cvv') }}" required>
                                    @error('card_cvv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="section-box">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">₡{{ number_format($total, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Envío:</span>
                                <span id="shipping-cost">₡5,500</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h4>Total:</h4>
                                <h4 class="text-primary" id="grand-total">₡{{ number_format($total + 5500, 0) }}</h4>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Confirmar Pedido
                            </button>
                            <a href="{{ route('techstore.cart') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver al Carrito
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variables del servidor
        const subtotalAmount = {{ $total }};
        const shippingMethods = {!! json_encode($shippingMethods) !!};

        // Formatear números y validar campos de tarjeta
        document.getElementById('card_number').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });

        // Formatear fecha
        document.getElementById('card_expiry').addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            this.value = value;
        });

        // Solo números en CVV
        document.getElementById('card_cvv').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });

        // Actualizar total con envío
        function updateTotal(method) {
            const shippingCost = shippingMethods[method].cost;
            const grandTotal = subtotalAmount + shippingCost;
            document.getElementById('shipping-cost').textContent = '₡' + shippingCost.toLocaleString();
            document.getElementById('grand-total').textContent = '₡' + grandTotal.toLocaleString();
        }
    </script>
</body>
</html>