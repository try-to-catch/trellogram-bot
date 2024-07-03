<?php

namespace App\Services\Trello\Entities;

/**
 * Class Data
 *
 * @method mixed|null getOld()
 * @method mixed getBoard()
 */
class Data extends Entity
{
    public function getCard(): ?Card
    {
        $card = $this->getProperty('card');

        if (!$card) {
            return null;
        }

        return app(Card::class, ['data' => $card]);
    }

    public function getList(): ?ListEntity
    {
        $list = $this->getProperty('list');

        if (!$list) {
            return null;
        }

        return app(ListEntity::class, ['data' => $list]);
    }

    public function getListBefore(): ?ListEntity
    {
        $list = $this->getProperty('listBefore');

        if (!$list) {
            return null;
        }

        return app(ListEntity::class, ['data' => $list]);
    }

    public function getListAfter(): ?ListEntity
    {
        $list = $this->getProperty('listAfter');

        if (!$list) {
            return null;
        }

        return app(ListEntity::class, ['data' => $list]);
    }
}
