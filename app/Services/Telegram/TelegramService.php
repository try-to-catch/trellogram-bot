<?php

namespace App\Services\Telegram;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class TelegramService
{
    public function __construct(private readonly string $chatId)
    {
    }

    /**
     * @throws TelegramException
     */
    public function sendMessage(string $message, array $data): void
    {
        Request::sendMessage([
            'chat_id' => $this->chatId,
            'text' => $message,
            ...$data
        ]);
    }
}
