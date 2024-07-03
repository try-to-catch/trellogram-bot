<?php

namespace App\Services\Trello;

use App\Services\Trello\Entities\Trello;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Class TrelloService
 *
 *
 */
class TrelloService
{
    public function __construct(
        private readonly string $key,
        private readonly string $token
    )
    {
    }

    public static function wrapWebhookUpdate(Request $request): Trello
    {
        return app(Trello::class, ['data' => $request->all()]);
    }

    protected function get(string $url): Response
    {
        $query = [
            'key' => $this->key,
            'token' => $this->token,
        ];

        return Http::get($url, $query);
    }

    public function getBoardMembers(string $boardId): Response
    {
        return $this->get("https://api.trello.com/1/boards/{$boardId}/members");
    }

    public function getBoardCards(string $boardId): Response
    {
        return $this->get("https://api.trello.com/1/boards/{$boardId}/cards");
    }
}
