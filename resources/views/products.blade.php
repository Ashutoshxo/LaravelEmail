<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products - Email Funnel Demo</title>
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
                    @auth
                        <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">🛒 Cart</a>
                        <span class="text-gray-600">Hi, {{ auth()->user()->name }}</span>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @guest
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                Please <a href="{{ route('login') }}" class="underline font-semibold">login</a> to add items to cart
            </div>
        @endguest

        <h2 class="text-3xl font-bold mb-6">Our Products</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $products = [
                ['id' => 1, 'name' => 'iPhone 15 Pro', 'price' => 79999, 'image' => 'https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/iphone-15-model-unselect-gallery-1-202309?wid=2560&hei=1440&fmt=jpeg&qlt=95&.v=1692810665888'],
                ['id' => 2, 'name' => 'AirPods Pro', 'price' => 24900, 'image' => 'https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/MTJV3?wid=1200&hei=630&fmt=jpeg&qlt=95&.v=1694014871985'],
                ['id' => 3, 'name' => 'MacBook Pro M3', 'price' => 199900, 'image' => 'https://th.bing.com/th/id/OIP.aNiFefzNvWMZwR-RNcoD2gHaD4?w=307&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'],
                ['id' => 4, 'name' => 'Apple Watch Ultra', 'price' => 89900, 'image' => 'https://www.apple.com/newsroom/images/2024/09/introducing-apple-watch-series-10/article/Apple-Watch-Series-10-hero-240909_big.jpg.large_2x.jpg'],
                ['id' => 5, 'name' => 'iPad Air', 'price' => 59900, 'image' => 'https://cdn.mos.cms.futurecdn.net/sVCJUxy8wHnKtdF84yf2NK.jpg'],
                ['id' => 6, 'name' => 'HomePod Mini', 'price' => 9900, 'image' => 'https://tse2.mm.bing.net/th/id/OIP.4qAYk07gQmfxDhSD0zREigHaEK?rs=1&pid=ImgDetMain&o=7&rm=3'],
            ];
            @endphp

            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $product['name'] }}</h3>
                    <p class="text-2xl text-blue-600 font-bold mb-4">₹{{ number_format($product['price']) }}</p>
                    
                    @auth
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                        <input type="hidden" name="name" value="{{ $product['name'] }}">
                        <input type="hidden" name="price" value="{{ $product['price'] }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                            Add to Cart
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="block w-full bg-gray-400 text-white py-2 px-4 rounded-lg text-center">
                        Login to Buy
                    </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>

   
    @auth
    <script>
    (function() {
        // Configuration
        const THRESHOLD_MS = {{ config('email-funnel.website_stay_minutes', 10) }} * 60 * 1000;
        let startTime = Date.now();
        let timerInterval = null;
        
        let emailSent = localStorage.getItem('help_email_sent_today');
        let lastSentDate = localStorage.getItem('help_email_sent_date');
        
        const today = new Date().toDateString();
        
      
        if (lastSentDate !== today) {
            localStorage.removeItem('help_email_sent_today');
            emailSent = null;
        }
        
        if (emailSent === 'true') {
            console.log('✅ Help email already sent today. Timer disabled.');
            return;
        }
        
        console.log('⏱️ Timer started. Will trigger after {{ config('email-funnel.website_stay_minutes', 10) }} minutes.');
        
       
        timerInterval = setInterval(function() {
            const elapsed = Date.now() - startTime;
            const elapsedMinutes = Math.floor(elapsed / 60000);
            
            console.log(`⏱️ Time elapsed: ${elapsedMinutes} minute(s)`);
            
            if (elapsed >= THRESHOLD_MS && emailSent !== 'true') {
                console.log('🎯 Threshold reached! Sending help email...');
                clearInterval(timerInterval);
                sendHelpEmail();
            }
        }, 30000); // 30 seconds
        
        function sendHelpEmail() {
            const timeSpentSeconds = Math.floor((Date.now() - startTime) / 1000);
            
            console.log('📤 Sending API request...');
            console.log('📊 Time spent:', timeSpentSeconds, 'seconds');
            
            fetch('/api/user/send-help-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    time_spent_seconds: timeSpentSeconds,
                    session_id: 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
                })
            })
            .then(response => {
                console.log('📥 Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('📥 API Response:', data);
                
                if (data.success) {
                    console.log('✅ Help email sent successfully!');
                    localStorage.setItem('help_email_sent_today', 'true');
                    localStorage.setItem('help_email_sent_date', today);
                } else {
                    console.log('ℹ️ Email not sent:', data.message);
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
            });
        }
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
        });
    })();
    </script>
    @endauth
</body>
</html>