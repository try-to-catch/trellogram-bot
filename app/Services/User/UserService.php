<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Longman\TelegramBot\Entities\Chat;

class UserService
{
    public function updateOrCreateUser(array $attributes, array $values = []): Builder|Model|User
    {
        return User::query()->updateOrCreate($attributes, $values);
    }

    public function updateOrCreateUserByChat(Chat $chat): Builder|Model|User
    {
        $username = $chat->getUsername();

        return $this->updateOrCreateUser(
            [
                'chat_id' => $chat->getId(),
            ],
            [
                'first_name' => $chat->getFirstName(),
                'last_name' => $chat->getLastName(),
                'username' => $username ? strtolower($username) : null,
            ]
        );
    }

    public function updateUserTrelloIdByChatId(int $chatId, string $trelloId): int
    {
        return User::query()
            ->where('chat_id', $chatId)
            ->update(['trello_id' => $trelloId]);
    }

    public function getAllUsers(): Collection|array
    {
        return User::query()->get();
    }

    public function getUserByUsername(string $username): Model|User|null
    {
        return User::query()->where('username', $username)->first();
    }
}
