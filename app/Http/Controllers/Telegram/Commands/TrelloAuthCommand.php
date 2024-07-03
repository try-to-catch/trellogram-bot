<?php

namespace App\Http\Controllers\Telegram\Commands;

use App\Services\Trello\TrelloService;
use App\Services\User\UserService;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class TrelloAuthCommand extends SystemCommand
{
    protected $name = 'trello_auth';
    protected $description = 'Connect trello account command';
    protected $usage = '/trello_auth <trello_username>';
    protected $version = '1.0.0';
    protected $private_only = true;

    /**
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        /**
         * @var TrelloService $trelloService
         * @var UserService $userService
         */
        $trelloService = app(TrelloService::class);
        $userService = app(UserService::class);

        $chat = $this->getMessage()->getChat();
        $text = $this->getMessage()->getText(true);

        $members = collect($trelloService->getBoardMembers(config('services.trello.board_id'))->json());

        $member = $members->first(function ($member) use ($text) {
            return $member['username'] === $text;
        });

        if (!$member) {
            return $this->replyToChat('Wrong username or member is not in the board.');
        }

        $userService->updateUserTrelloIdByChatId($chat->getId(), $member['id']);

        return $this->replyToChat('Trello account connected successfully.');
    }
}
