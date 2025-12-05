Question 1: How will you avoid sending emails to bots?
Answer: Three-layer bot protection:
Layer 1 - User Agent Check:
phpprivate function isBot(Request $request): bool
{
    $userAgent = strtolower($request->userAgent() ?? '');
    
    if (empty($userAgent)) return true;
    
    $botPatterns = ['bot', 'crawler', 'spider', 'headless'];
    
    foreach ($botPatterns as $pattern) {
        if (stripos($userAgent, $pattern) !== false) return true;
    }
    return false;
}
Layer 2 - Email Validation:
private function isValidEmail(string $email): bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    
    $botEmails = ['test@test.com', 'bot@', 'fake@'];
    foreach ($botEmails as $pattern) {
        if (stripos($email, $pattern) !== false) return false;
    }
    return true;
}
Layer 3 - Activity Tracking (Frontend):

let lastActivity = Date.now();
['mousedown', 'keydown', 'scroll'].forEach(event => {
    document.addEventListener(event, () => lastActivity = Date.now());
});


if (Date.now() - lastActivity > 120000) return; // Don't send

Question 2: Where will you store time thresholds?
Answer: Both .env and config file:
In .env (Primary):
envABANDONED_CART_MINUTES=30
WEBSITE_STAY_MINUTES=10
In config/email-funnel.php (Secondary):
phpreturn [
    'abandoned_cart_minutes' => env('ABANDONED_CART_MINUTES', 30),
    'website_stay_minutes' => env('WEBSITE_STAY_MINUTES', 10),
];
Usage:
php$minutes = config('email-funnel.abandoned_cart_minutes');
Why both?

.env = Environment-specific values (dev/prod)
Config = Fallback defaults + cacheable


Question 3: How to test both features?
Test 1: Abandoned Cart Email
php artisan tinker

$user = \App\Models\User::factory()->create(['email' => 'test@example.com']);
\App\Models\Cart::create([
    'user_id' => $user->id,
    'items' => [['product_id' => 1, 'name' => 'iPhone', 'quantity' => 1, 'price' => 79999]],
    'total_amount' => 79999,
    'last_activity_at' => now()->subMinutes(31)
]);
exit

php artisan carts:process-abandoned


php artisan queue:work --once

=
Test 2: Website Stay Email
php artisan tinker
php// Create test activity
$user = \App\Models\User::first();
$activity = \App\Models\UserActivity::create([
    'user_id' => $user->id,
    'session_id' => 'test-' . time(),
    'time_spent_seconds' => 600,
    'session_started_at' => now()->subMinutes(10),
    'last_activity_at' => now(),
]);


\Mail::to($user->email)->send(new \App\Mail\WebsiteStayHelpEmail($user, 10));
$activity->update(['help_email_sent_at' => now()]);
exit
