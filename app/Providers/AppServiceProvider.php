<?php

namespace App\Providers;

use App\Services\Telegram\TelegramService;
use App\Services\Trello\TrelloService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(TelegramService::class, function () {
            return new TelegramService(config('services.telegram.chat_id'));
        });

        $this->app->singleton(TrelloService::class, function () {
            return new TrelloService(
                config('services.trello.key'),
                config('services.trello.token')
            );
        });
    }
}
