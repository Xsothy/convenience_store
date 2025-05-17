<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order #{{ $order->uuid }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #333;
        }
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-details, .customer-details {
            float: left;
            width: 50%;
        }
        .order-details h2, .customer-details h2, .items-list h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .totals {
            width: 300px;
            float: right;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
        }
        .totals th {
            text-align: left;
        }
        .totals td {
            text-align: right;
        }
        .total-row td, .total-row th {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <p>{{ config('app.url') }}</p>
        </div>

        <div class="order-info clearfix">
            <div class="order-details">
                <h2>Order Information</h2>
                <p><strong>Order Number:</strong> #{{ $order->uuid }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                <p><strong>Order Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            </div>

            <div class="customer-details">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> {{ $order->name }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                @if($order->flat)
                <p><strong>Flat/Unit:</strong> {{ $order->flat }}</p>
                @endif
                <p><strong>Email:</strong> {{ $order->user->email ?? 'Not provided' }}</p>
            </div>
        </div>

        <div class="items-list">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>VAT</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $currency }} {{ number_format($item->price, 2) }}</td>
                        <td>{{ $currency }} {{ number_format($item->discount, 2) }}</td>
                        <td>{{ $currency }} {{ number_format($item->vat, 2) }}</td>
                        <td>{{ $currency }} {{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal:</th>
                    <td>{{ $currency }} {{ number_format($order->total - $order->shipping - $order->vat + $order->discount, 2) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <th>Discount:</th>
                    <td>{{ $currency }} {{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                @if($order->vat > 0)
                <tr>
                    <th>VAT:</th>
                    <td>{{ $currency }} {{ number_format($order->vat, 2) }}</td>
                </tr>
                @endif
                @if($order->shipping > 0)
                <tr>
                    <th>Shipping:</th>
                    <td>{{ $currency }} {{ number_format($order->shipping, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <th>Total:</th>
                    <td>{{ $currency }} {{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="clearfix"></div>

        @if($order->notes)
        <div class="notes">
            <h2>Notes</h2>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Thank you for your order!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 