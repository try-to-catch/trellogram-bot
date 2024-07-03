<?php

namespace App\Services\Trello\Entities;

class Trello extends Entity
{

    public function getAction(): Action
    {
        return app(Action::class, ['data' => $this->getProperty('action')]);
    }
}
