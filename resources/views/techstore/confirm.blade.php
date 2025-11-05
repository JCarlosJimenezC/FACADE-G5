<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Orden - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .confirm-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .total-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="confirm-container">
                    <div class="text-center mb-4">
                        <i class="bi bi-clipboard-check" style="font-size: 4rem; color: #667eea;"></i>
                        <h2 class="mt-3">Confirmar Pedido</h2>
                        <p class="text-muted">Revise los detalles antes de confirmar</p>
                    </div>

                    <!-- Productos -->
                    <div class="info-section">
                        <h5><i class="bi bi-box-seam"></i> Productos</h5>
                        <hr>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">${{ number_format($item['unit_price'], 2) }}</td>
                                    <td class="text-end">${{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cliente -->
                    <div class="info-section">
                        <h5><i class="bi bi-person-circle"></i> Información del Cliente</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Nombre:</strong> {{ $order['customer']['name'] }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Email:</strong> {{ $order['customer']['email'] }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Teléfono:</strong> {{ $order['customer']['phone'] }}
                            </div>
                            <div class="col-md-12 mb-2">
                                <strong>Dirección:</strong> {{ $order['customer']['address'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Envío -->
                    <div class="info-section">
                        <h5><i class="bi bi-truck"></i> Envío</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Método:</strong> 
                                {{ $order['shipping']['method'] == 'express' ? 'Express' : 'Estándar' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Costo:</strong> ${{ number_format($order['shipping']['cost'], 2) }}
                            </div>
                            <div class="col-md-12">
                                <strong>Entrega estimada:</strong> {{ $order['shipping']['estimated_delivery'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Pago -->
                    <div class="info-section">
                        <h5><i class="bi bi-credit-card"></i> Pago</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Tarjeta:</strong> **** {{ $order['payment']['card_last4'] }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Titular:</strong> {{ $order['payment']['card_holder'] }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>ID Transacción:</strong> {{ $order['payment']['transaction_id'] }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Estado:</strong> 
                                <span class="badge bg-success">{{ strtoupper($order['payment']['status']) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="total-box">
                        <h3>Total a Pagar</h3>
                        <h1 class="display-3">${{ number_format($order['total'], 2) }}</h1>
                    </div>

                    <!-- Botones -->
                    <form action="{{ route('techstore.confirm.order', $order['id']) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Confirmar y Finalizar Compra
                            </button>
                            <a href="{{ route('techstore.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Cancelar Pedido
                            </a>
                        </div>
                    </form>

                    <div class="alert alert-info mt-4 text-center">
                        <i class="bi bi-info-circle"></i> 
                        Al confirmar, se actualizará el inventario y recibirás un número de seguimiento.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>