<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">🛍️ Shopping Store</h1>
                <div class="flex gap-4">
                    <a href="{{ route('products') }}" class="text-gray-600 hover:text-gray-900">Products</a>
                    <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">🛒 Cart</a>
                    <span class="text-gray-600">Hi, {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-3xl font-bold mb-6">Your Shopping Cart</h2>

        @if(!$cart || empty($cart->items))
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-500 text-xl mb-4">Your cart is empty</p>
                <a href="{{ route('products') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md">
                @foreach($cart->items as $item)
                <div class="flex items-center gap-4 p-6 border-b">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-gray-600">₹{{ number_format($item['price']) }} each</p>
                    </div>
                    
                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                               min="1" max="10" 
                               class="w-20 px-3 py-2 border rounded">
                        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Update
                        </button>
                    </form>
                    
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600">
                            ₹{{ number_format($item['price'] * $item['quantity']) }}
                        </p>
                    </div>
                    
                    <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach

                <div class="p-6 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xl font-semibold">Total:</span>
                        <span class="text-3xl font-bold text-blue-600">₹{{ number_format($cart->total_amount) }}</span>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('products') }}" class="flex-1 bg-gray-600 text-white text-center py-3 rounded-lg hover:bg-gray-700">
                            Continue Shopping
                        </a>
                        <button class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                            Checkout (Demo)
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 mt-4 text-center">
                        💡 Tip: If you leave without checkout, we'll send you a reminder email in {{ config('email-funnel.abandoned_cart_minutes', 30) }} minutes!
                    </p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>