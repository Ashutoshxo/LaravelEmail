<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Mail\AbandonedCartEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessAbandonedCarts extends Command
{
    protected $signature = 'carts:process-abandoned';
    
    protected $description = 'Send emails to users with abandoned carts';

    public function handle()
    {
        $this->info('🔍 Checking for abandoned carts...');
        
        $minutes = config('email-funnel.abandoned_cart_minutes', 30);
        
        $abandonedCarts = Cart::abandoned($minutes)
            ->with('user')
            ->get();
        
        if ($abandonedCarts->isEmpty()) {
            $this->info('✅ No abandoned carts found.');
            return 0;
        }
        
        $this->info("📧 Found {$abandonedCarts->count()} abandoned cart(s).");
        
        foreach ($abandonedCarts as $cart) {
            try {
                Mail::to($cart->user->email)->queue(new AbandonedCartEmail($cart));
                $cart->markEmailAsSent();
                $this->info("✅ Email queued for: {$cart->user->email}");
            } catch (\Exception $e) {
                $this->error("❌ Failed: {$e->getMessage()}");
            }
        }
        
        return 0;
    }
}