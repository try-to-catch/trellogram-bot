<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramNotificationService;
use App\Services\Trello\TrelloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Longman\TelegramBot\Exception\TelegramException;

class TrelloController extends Controller
{
    public function __invoke(Request $request, TelegramNotificationService $telegramNotificationService): JsonResponse
    {
        info('Trello Webhook');

        $trello = TrelloService::wrapWebhookUpdate($request);
        $action = $trello->getAction();

        if ($action->isType('updateCard')) {
            $data = $action->getData();

            try {
                $telegramNotificationService->trelloCardUpdated($data);

            } catch (TelegramException $e) {
                logger()->error($e->getMessage());
            }
        }

        return response()->json();
    }
}
