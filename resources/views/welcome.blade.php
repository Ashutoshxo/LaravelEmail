<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Funnel Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-16">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">📧 Email Funnel System</h1>
            <p class="text-xl text-gray-600 mb-8">Automated Cart & Website Activity Emails</p>
            
            <div class="flex justify-center gap-4">
                <a href="{{ route('products') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 shadow-lg">
                    View Products
                </a>
                @guest
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 shadow-lg border-2 border-blue-600">
                        Login
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" class="bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-700 shadow-lg">
                        Go to Cart
                    </a>
                @endguest
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-4xl mb-4">🛒</div>
                <h3 class="text-2xl font-bold mb-3">Abandoned Cart Emails</h3>
                <p class="text-gray-600 mb-4">
                    Add items to cart and leave without checkout. You'll receive an email reminder after 
                    <strong>{{ config('email-funnel.abandoned_cart_minutes', 30) }} minutes</strong>.
                </p>
                <ul class="text-sm text-gray-500 space-y-2">
                    <li>✅ Automatic email reminders</li>
                    <li>✅ No duplicate emails</li>
                    <li>✅ Bot protection enabled</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-4xl mb-4">⏱️</div>
                <h3 class="text-2xl font-bold mb-3">Website Stay Emails</h3>
                <p class="text-gray-600 mb-4">
                    Browse the website for <strong>{{ config('email-funnel.website_stay_minutes', 10) }} minutes</strong> 
                    and get a helpful assistance email.
                </p>
                <ul class="text-sm text-gray-500 space-y-2">
                    <li>✅ JavaScript timer tracking</li>
                    <li>✅ One email per day max</li>
                    <li>✅ Activity-based triggering</li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold mb-4">🔧 Technical Features</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-semibold text-lg mb-2">Backend</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Laravel 12</li>
                        <li>• Artisan Commands</li>
                        <li>• Queue System</li>
                        <li>• Task Scheduler</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-2">Email System</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• SMTP Configuration</li>
                        <li>• Mailable Classes</li>
                        <li>• Queue Workers</li>
                        <li>• Email Templates</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-lg mb-2">Protection</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Bot Detection</li>
                        <li>• Rate Limiting</li>
                        <li>• Duplicate Prevention</li>
                        <li>• Activity Tracking</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-600">
            <p>💡 To test: Login → Add products → Leave cart → Wait {{ config('email-funnel.abandoned_cart_minutes', 30) }} min → Check email</p>
        </div>
    </div>
</body>
</html>