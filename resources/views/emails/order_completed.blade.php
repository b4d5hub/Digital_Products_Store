<!DOCTYPE html>
<html>
<head>
    <title>Your Order is Completed</title>
</head>
<body>
    <h1>Order Completed</h1>
    <p>Thank you for your purchase!</p>
    <p>Order Number: {{ $order->order_number }}</p>
    <p>Product: {{ $order->product->name }}</p>
    <p>Quantity: {{ $order->quantity }}</p>
    <p>Total Price: ${{ $order->quantity * $order->unit_price }}</p>
    <p>You can download your product using the following link: <a href="{{ route('successtest', ['order_number' => $order->order_number, 'orderTimestamp' => strtotime($order->created_at), 'download_token' => $order->download_token]) }}">Download Link</a></p>
</body>
</html>
