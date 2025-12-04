<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Abandoned Cart Email - Har 5 minute mein run hoga
        $schedule->command('carts:process-abandoned')
                 ->everyFiveMinutes()
                 ->withoutOverlapping() // Ek bar mein ek hi run hoga
                 ->runInBackground()
                 ->onSuccess(function () {
                     \Log::info('Abandoned cart command completed successfully');
                 })
                 ->onFailure(function () {
                     \Log::error('Abandoned cart command failed');
                 });
        
        // Optional: Daily cleanup of old carts (30 days purani)
        $schedule->call(function () {
            \App\Models\Cart::where('updated_at', '<', now()->subDays(30))
                           ->delete();
        })->daily()->at('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}