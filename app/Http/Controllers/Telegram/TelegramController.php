<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Telegram;

class TelegramController extends Controller
{
    public function __invoke(Request $request): void
    {
        try {
            app(Telegram::class)->handle($request);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
