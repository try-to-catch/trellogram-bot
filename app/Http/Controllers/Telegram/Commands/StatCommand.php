<?php

namespace App\Http\Controllers\Telegram\Commands;

use App\Services\Trello\TrelloService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Collection;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class StatCommand extends SystemCommand
{

    protected $name = 'stat';
    protected $description = 'Connect trello account command';
    protected $usage = '/stat or /stat <trello_username>';
    protected $version = '1.0.0';
    protected $private_only = false;

    /**
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        /**
         * @var TrelloService $trelloService
         * @var UserService $userService
         **/
        $trelloService = app(TrelloService::class);
        /**  */
        $userService = app(UserService::class);

        $text = $this->getMessage()->getText(true);

        // Fetch and cache Trello board cards
        $cards = collect($this->getTrelloBoardCards($trelloService));

        if ($text && $text !== 'Звіт') {
            return $this->handleUserSpecificStat($text, $userService, $cards);
        }

        return $this->handleGroupStat($userService, $cards);
    }

    /**
     * @throws TelegramException
     */
    private function getTrelloBoardCards(TrelloService $trelloService): array
    {
        $messageId = $this->getMessage()->getMessageId();
        try {
            return $trelloService->getBoardCards(config('services.trello.board_id'))->json();
        } catch (Exception $e) {
            $this->replyToChat('Failed to retrieve Trello board cards.',
                [
                    'reply_to_message_id' => $messageId
                ]);
            logger()->error($e->getMessage());
            return [];
        }
    }

    /**
     * @throws TelegramException
     */
    private function handleUserSpecificStat(string $username, UserService $userService, Collection $cards): ServerResponse
    {
        $messageId = $this->getMessage()->getMessageId();

        $user = $userService->getUserByUsername($username);
        if (!$user) {
            return $this->replyToChat('Користувач не зареєстрован в системі',
                [
                    'reply_to_message_id' => $messageId
                ]);
        }

        if (!$user->trello_id) {
            return $this->replyToChat('Користувач не підключив Trello',
                [
                    'reply_to_message_id' => $messageId
                ]);
        }

        $cardsCount = $cards->filter(function ($card) use ($user) {
            return in_array($user->trello_id, $card['idMembers']);
        })->count();

        return $this->replyToChat("Кількість завдань у користувача: $cardsCount",
            [
                'reply_to_message_id' => $messageId
            ]);
    }

    /**
     * @throws TelegramException
     */
    private function handleGroupStat(UserService $userService, Collection $cards): ServerResponse
    {
        $messageId = $this->getMessage()->getMessageId();
        $users = $userService->getAllUsers();

        $users = $users->map(function ($user) use ($cards) {
            $cardsCount = $cards->filter(function ($card) use ($user) {
                return in_array($user->trello_id, $card['idMembers']);
            })->count();

            $user = collect($user->toArray());
            return $user->put('cards_count', $cardsCount);
        });

        $statistic = '';

        $users->sortByDesc('cards_count')->each(function ($user) use (&$statistic) {
            $statistic .= "{$user->get('first_name')} " .
                ($user->get('last_name') ?? "[{$user->get('username')}]") .
                " - {$user->get('cards_count')}" . PHP_EOL;
        });

        return $this->replyToChat('📊 Статистика по завданням учасників групи:' . PHP_EOL . PHP_EOL . $statistic,
            [
                'reply_to_message_id' => $messageId
            ]);
    }
}
