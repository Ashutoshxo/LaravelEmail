<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use App\Mail\WebsiteStayHelpEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserActivityController extends Controller
{
    public function sendHelpEmail(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'time_spent_seconds' => 'required|integer|min:0',
            'session_id' => 'required|string',
        ]);
        
        $timeSpentSeconds = $validated['time_spent_seconds'];
        $sessionId = $validated['session_id'];
        
        Log::info('Help email request received', [
            'user_id' => $user->id,
            'time_spent' => $timeSpentSeconds,
        ]);
        
        if ($this->isBot($request)) {
            Log::info('Bot detected, skipping help email', [
                'user_id' => $user->id,
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bot detected'
            ]);
        }
        
      
        $todayActivity = UserActivity::where('user_id', $user->id)
            ->whereDate('help_email_sent_date', today())
            ->first();
        
        if ($todayActivity) {
            Log::info('Help email already sent today', [
                'user_id' => $user->id,
                'sent_at' => $todayActivity->help_email_sent_at,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Help email already sent today'
            ]);
        }
        
        $requiredSeconds = config('email-funnel.website_stay_minutes', 10) * 60;
        
        if ($timeSpentSeconds < $requiredSeconds) {
            return response()->json([
                'success' => false,
                'message' => "Time threshold not met. Need {$requiredSeconds} seconds, got {$timeSpentSeconds}"
            ]);
        }
        
        $activity = UserActivity::updateOrCreate(
            [
                'user_id' => $user->id,
                'session_id' => $sessionId,
            ],
            [
                'time_spent_seconds' => $timeSpentSeconds,
                'session_started_at' => now()->subSeconds($timeSpentSeconds),
                'last_activity_at' => now(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]
        );
      
        try {
            $timeSpentMinutes = round($timeSpentSeconds / 60);
            
            
            Mail::to($user->email)
                ->queue(new WebsiteStayHelpEmail($user, $timeSpentMinutes));
            
           
            $activity->markHelpEmailAsSent();
            
            Log::info('Website stay help email sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'time_spent_minutes' => $timeSpentMinutes,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Help email sent successfully',
                'time_spent_minutes' => $timeSpentMinutes,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send website stay help email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
    
  
    private function isBot(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');
        
       
        if (empty($userAgent)) {
            return true;
        }
        
     
        $botPatterns = config('email-funnel.bot_user_agents', [
            'bot', 'crawler', 'spider', 'scraper', 
            'headless', 'phantom', 'selenium'
        ]);
        
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }
        
       
        if (stripos($userAgent, 'headless') !== false) {
            return true;
        }
        
        return false;
    }
}