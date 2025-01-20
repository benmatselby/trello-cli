<?php

namespace TrelloCli\Sorter;

use TrelloCli\Sorter;

/**
 * Responsible for sorting the cards from Trello by Date Created
 */
class DateCreated implements Sorter
{
    /**
     * @inheritdoc
     *
     * @param array<mixed> $cards The cards to be sorted
     *
     * @return array<mixed>
     */
    public function sort(array $cards): array
    {
        $data = [];
        foreach ($cards as $card) {
            $created = new \DateTime();
            $created->setTimestamp((int) hexdec(substr($card['id'], 0, 8)));

            $card['name'] = $created->format('Y-m-d H:i:s') . ' ' . $card['name'];
            $data[$created->getTimestamp()] = $card;
        }

        sort($data);
        return $data;
    }
}
