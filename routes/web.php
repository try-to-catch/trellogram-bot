<?php

use App\Http\Controllers\Telegram\TelegramController;
use App\Http\Controllers\TrelloController;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook/' . config('services.telegram.webhook'),
    TelegramController::class
);

Route::post('/trello/webhook/' . config('services.trello.webhook'),
    TrelloController::class
);

// This route is used to verify the Trello Webhook
Route::get('/trello/webhook/' . config('services.trello.webhook'), function () {
    return response()->json();
});
