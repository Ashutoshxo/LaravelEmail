<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id');
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('session_started_at')->nullable();  // ✅ nullable added
            $table->timestamp('last_activity_at')->nullable();    // ✅ nullable added
            $table->timestamp('help_email_sent_at')->nullable();
            $table->date('help_email_sent_date')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('help_email_sent_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};