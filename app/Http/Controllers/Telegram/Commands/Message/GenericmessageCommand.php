<?php

namespace App\Http\Controllers\Telegram\Commands\Message;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    /**
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $message_text = $message->getText(true);

        if ($message_text === 'Звіт') {
            $this->telegram->executeCommand('stat');
        }

        return Request::emptyResponse();
    }
}
