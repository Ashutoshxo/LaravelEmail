<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10B981; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .btn { display: inline-block; padding: 12px 30px; background: #10B981; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👋 Hi {{ $user->name }}!</h1>
        </div>
        <div class="content">
            <h2>You've been browsing for {{ $timeSpent }} minutes</h2>
            <p>Need any help? We're here for you! 😊</p>
            <a href="{{ url('/contact') }}" class="btn">Contact Support</a>
        </div>
    </div>
</body>
</html>