<?php

namespace App\Services\Trello\Entities;

/**
 * Class Action
 *
 * @method string getId()
 * @method string getIdMemberCreator()
 * @method string getType()
 * @method bool isType(string $type)
 * @method string getDate()
 * @method
 */
class Action extends Entity
{
    public function getData(): ?Data
    {
        $data = $this->getProperty('data');

        if (!$data) {
            return null;
        }

        return app(Data::class, ['data' => $data]);
    }

    public function getDisplay(): ?Display
    {
        $display = $this->getProperty('display');

        if (!$display) {
            return null;
        }

        return app(Display::class, ['data' => $display]);
    }
}
