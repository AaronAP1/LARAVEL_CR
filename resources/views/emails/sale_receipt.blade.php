<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de tu Compra</title>
</head>
<body>
    <h1>Gracias por tu compra, {{ $sale->client->name }}!</h1>
    <p>Aquí tienes los detalles de tu compra:</p>
    <ul>
        @foreach($sale->details as $detail)
            <li>{{ $detail->product->name }} - Cantidad: {{ $detail->quantity }} - Precio: ${{ $detail->total_price }}</li>
        @endforeach
    </ul>
    <p>Total: ${{ $sale->total_amount }}</p>
</body>
</html>