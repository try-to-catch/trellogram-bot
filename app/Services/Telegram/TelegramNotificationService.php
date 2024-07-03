<?php

namespace App\Services\Telegram;

use App\Services\Trello\Entities\Data;
use Longman\TelegramBot\Exception\TelegramException;

class TelegramNotificationService
{
    public function __construct(private TelegramService $telegramService)
    {
    }

    /**
     * @throws TelegramException
     */
    public function trelloCardUpdated(Data $data): void
    {
        $listBefore = $data->getListBefore()?->getName();

        if (!$listBefore) {
            return;
        }

        $this->telegramService->sendMessage(
            "🃏<b>Оновлена картка:</b> {$data->getCard()?->getName()}" . PHP_EOL .
            "📋<b>Список раніше:</b> {$listBefore}" . PHP_EOL .
            "📋<b>Список зараз:</b> {$data->getListAfter()?->getName()}",
            [
                'parse_mode' => 'HTML'
            ]
        );
    }
}
