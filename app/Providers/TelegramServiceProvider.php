<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class TelegramServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Telegram::class, function () {
            $telegram = new Telegram(
                config('services.telegram.token'),
                config('services.telegram.bot_username')
            );

            $telegram->addCommandsPaths([
                app_path('Http/Controllers/Telegram/Commands'),
            ]);

            return $telegram;
        });

        Request::initialize(app(Telegram::class));
    }
}
