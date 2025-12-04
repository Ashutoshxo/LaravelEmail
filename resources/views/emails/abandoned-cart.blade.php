<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .btn { display: inline-block; padding: 12px 30px; background: #4F46E5; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hi {{ $cart->user->name }}! 👋</h1>
        </div>
        <div class="content">
            <h2>You left items in your cart!</h2>
            <p>Total: ₹{{ number_format($cart->total_amount, 2) }}</p>
            <a href="{{ url('/cart') }}" class="btn">Complete Your Order</a>
        </div>
    </div>
</body>
</html>