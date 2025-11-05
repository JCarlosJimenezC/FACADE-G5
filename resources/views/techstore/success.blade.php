<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa - TechStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .success-container {
            background: white;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            100% { transform: scale(1); }
        }
        .order-details {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="success-container text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-lg" style="font-size: 4rem; color: white;"></i>
                    </div>
                    
                    <h1 class="display-4 text-success mb-3">¡Compra Exitosa!</h1>
                    <p class="lead text-muted mb-4">
                        Tu pedido ha sido confirmado. Recibirás un email de confirmación.
                    </p>

                    <div class="alert alert-success">
                        <strong><i class="bi bi-receipt"></i> Orden #{{ $order['id'] }}</strong><br>
                        <strong><i class="bi bi-truck"></i> Tracking: {{ $order['shipping']['tracking_number'] ?? 'Generando...' }}</strong>
                    </div>

                    <!-- Detalles -->
                    <div class="order-details text-start">
                        <h4 class="mb-4"><i class="bi bi-clipboard-data"></i> Resumen de tu Compra</h4>
                        
                        <div class="mb-3">
                            <strong>Productos:</strong>
                            <ul class="mt-2">
                                @foreach($order['items'] as $item)
                                    <li>{{ $item['name'] }} x {{ $item['quantity'] }} - ₡{{ number_format($item['subtotal'], 0) }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Cliente:</strong><br>
                                {{ $order['customer']['name'] }}<br>
                                {{ $order['customer']['email'] }}<br>
                                {{ $order['customer']['phone'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Envío:</strong><br>
                                {{ $order['shipping']['method'] == 'express' ? 'Express' : 'Estándar' }}<br>
                                Entrega: {{ $order['shipping']['estimated_delivery'] }}<br>
                                Costo: ₡{{ number_format($order['shipping']['cost'], 0) }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Dirección de Envío:</strong><br>
                            {{ $order['customer']['address'] }}
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <h4>Total Pagado:</h4>
                            <h4 class="text-success">₡{{ number_format($order['total'], 0) }}</h4>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Método de Pago:</strong><br>
                                Tarjeta **** {{ $order['payment']['card_last4'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha:</strong><br>
                                {{ $order['created_at'] }}
                            </div>
                        </div>

                        <div class="mt-3">
                            <strong>Estado:</strong>
                            <span class="badge bg-success">{{ strtoupper($order['status']) }}</span>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle"></i> 
                        Recibirás actualizaciones del envío por email
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('techstore.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Volver a la Tienda
                        </a>
                    </div>

                    <p class="text-muted mt-4 mb-0">
                        <small>
                            <i class="bi bi-question-circle"></i> 
                            ¿Preguntas? Contáctanos a soporte@techstore.com
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>