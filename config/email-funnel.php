<?php

return [

    'abandoned_cart_minutes' => env('ABANDONED_CART_MINUTES', 30),

    'website_stay_minutes' => env('WEBSITE_STAY_MINUTES', 10),

    'help_email_once_per_day' => env('HELP_EMAIL_ONCE_PER_DAY', true),

    'bot_user_agents' => [
        'bot',
        'crawler',
        'spider',
        'scraper',
        'headless',
        'phantom',
        'selenium',
    ],

    'excluded_email_domains' => [
        'test.com',
        'example.com',
        'fake.com',
    ],

];
