📧 Laravel Email Funnel System
A complete automated email marketing system built with Laravel 12 featuring abandoned cart recovery and website engagement tracking.
🎯 Project Overview
This project implements two automated email funnels:

Abandoned Cart Email Funnel - Sends reminder emails to users who leave items in their cart for 30+ minutes
Website Stay Funnel - Sends help emails to users who browse for 10+ minutes


✨ Features
1️⃣ Abandoned Cart Email Funnel
Functionality:

Automatically detects abandoned carts (30 minutes threshold)
Sends personalized reminder emails
Runs via scheduled command every 5 minutes
Prevents duplicate emails
Bot detection and filtering

Key Components:

Artisan command: carts:process-abandoned
Task scheduler (runs every 5 minutes)
Queue-based email sending
Database tracking to prevent duplicates

2️⃣ Website Stay Email Funnel
Functionality:

JavaScript timer tracks user browsing time
Automatically sends help email after 10 minutes
Only one email per day per user
Activity-based verification (not just idle time)
Bot protection

Key Components:

Frontend JavaScript timer
Laravel API endpoint
Database activity tracking
Multi-layer duplicate prevention


Terminal 1 - Server:
bashphp artisan serve
Terminal 2 - Queue Worker:
bashphp artisan queue:work

php artisan tinker
php// Create test user
$user = \App\Models\User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password')
]);

// Create abandoned cart
\App\Models\Cart::create([
    'user_id' => $user->id,
    'items' => [
        ['product_id' => 1, 'name' => 'Test Product', 'quantity' => 1, 'price' => 100]
    ],
    'total_amount' => 100,
    'last_activity_at' => now()->subMinutes(31) // 31 minutes ago
]);

echo "Test cart created!\n";
exit

php artisan carts:process-abandoned