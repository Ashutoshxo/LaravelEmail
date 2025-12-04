Question 1: How will you avoid sending emails to bots?
Answer: Three-Layer Bot Protection System
Layer 1: User Agent Detection
phpprivate function isBot(Request $request): bool
{
    $userAgent = strtolower($request->userAgent() ?? '');
    

    if (empty($userAgent)) {
        return true; // Most bots don't send user agents
    }
    

    $botPatterns = [
        'bot', 'crawler', 'spider', 'scraper',
        'headless', 'phantom', 'selenium',
        'curl', 'wget', 'python', 'java'
    ];
    
    foreach ($botPatterns as $pattern) {
        if (stripos($userAgent, $pattern) !== false) {
            return true;
        }
    }
    
    return false;
}
How it works:

Checks if request has user agent (bots often don't send)
Matches against known bot keywords in user agent string
Returns true if bot detected, preventing email

Layer 2: Email Validation
phpprivate function isValidEmail(string $email): bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    $botPatterns = [
        'test@test.com',
        'bot@',
        'crawler@',
        'spam@',
        'fake@',
        'noreply@'
    ];
    
    foreach ($botPatterns as $pattern) {
        if (stripos($email, $pattern) !== false) {
            return false;
        }
    }
    
    return true;
}
Implementation in Command:
php// In ProcessAbandonedCarts command
foreach ($abandonedCarts as $cart) {
    if (!$this->isValidEmail($cart->user->email)) {
        $this->warn("Skipping invalid email: {$cart->user->email}");
        continue; // Skip this cart
    }
    
    Mail::to($cart->user->email)->queue(new AbandonedCartEmail($cart));
}

Question 2: Where will you store the time thresholds (.env / config)?

Answer: Both - Using Laravel’s Best Practices

Storage Location 1: .env File (Primary)
# .env
ABANDONED_CART_MINUTES=30
WEBSITE_STAY_MINUTES=10
HELP_EMAIL_ONCE_PER_DAY=true


Why .env?

✅ Different values per environment (dev/staging/production)

✅ Not committed to version control (secure)

✅ Easy to change without code deployment

✅ Environment-specific configuration

Example Use Cases:

Environment	ABANDONED_CART_MINUTES
Development	1 (quick testing)
Staging	5 (faster feedback)
Production	30 (real business logic)

Storage Location 2: Config File (Secondary)

<?php

return [
    // Get from .env with fallback defaults
    'abandoned_cart_minutes' => env('ABANDONED_CART_MINUTES', 30),
    'website_stay_minutes' => env('WEBSITE_STAY_MINUTES', 10),
    'help_email_once_per_day' => env('HELP_EMAIL_ONCE_PER_DAY', true),

    'bot_user_agents' => [
        'bot', 'crawler', 'spider', 'scraper',
        'headless', 'phantom', 'selenium',
    ],

    'excluded_email_domains' => [
        'test.com',
        'example.com',
        'fake.com',
    ],
];


Why Config File?

✅ Centralized configuration

✅ Default fallback values if .env is missing

✅ Type-safe access via config() helper

✅ Can be cached for performance

✅ Supports complex data structures


Question 3: Show how you would test both features
Step 1: Create Test Data
php artisan tinker

// Create test user
$user = \App\Models\User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password')
]);

// Create abandoned cart (31 minutes old)
\App\Models\Cart::create([
    'user_id' => $user->id,
    'items' => [
        [
            'product_id' => 1,
            'name' => 'iPhone 15 Pro',
            'quantity' => 1,
            'price' => 79999
        ],
        [
            'product_id' => 2,
            'name' => 'AirPods Pro',
            'quantity' => 2,
            'price' => 24900
        ]
    ],
    'total_amount' => 129799,
    'last_activity_at' => now()->subMinutes(31)
]);

echo "✅ Test cart created!\n";
exit;

php artisan carts:process-abandoned
.....ashutosh
