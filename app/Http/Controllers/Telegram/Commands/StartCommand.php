<?php

namespace App\Http\Controllers\Telegram\Commands;

use App\Services\User\UserService;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class StartCommand extends SystemCommand
{
    protected $name = 'start';
    protected $description = 'Start command';
    protected $usage = '/start';
    protected $version = '1.0.0';
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        /** @var UserService $userService */
        $userService = app(UserService::class);

        $chat = $this->getMessage()->getChat();
        $firstName = $chat->getFirstName();

        $userService->updateOrCreateUserByChat($chat);

        $keyboard[] = new Keyboard(
            ['Звіт'],
        );

        return $this->replyToChat(
            "Привіт, $firstName 👋." . PHP_EOL . PHP_EOL .
            "Я Trellogram бот 🤖, \nякий допомогає відстежувати оновлення 📋 у trello." . PHP_EOL . PHP_EOL .
            "Ви можете отримати статистику 📊 за допомогою команди /stat" . PHP_EOL . PHP_EOL .
            "Або отримати статистику певного користувача 👤 по його username використовуючи команду /stat <username> 🔍." . PHP_EOL . PHP_EOL .
            "Щоб підв'язати свій аккаунт Trello, використовуйте команду /trello_auth <trello_username> 🔄.",
            [
                'reply_markup' => end($keyboard)->setResizeKeyboard(true)->setSelective(false),
            ]
        );
    }
}
