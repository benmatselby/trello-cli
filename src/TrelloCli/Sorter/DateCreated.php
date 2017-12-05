<?php
/**
 * Date Created Sorter
 */

namespace TrelloCli\Sorter;

use TrelloCli\Sorter;

/**
 * Responsible for sorting the cards from Trello by Date Created
 */
class DateCreated implements Sorter
{
    /**
     * @inheritdoc
     */
    public function sort(array $cards)
    {
        $data = [];
        foreach ($cards as $card) {
            $cardName = $card['name'];
            $created = new \DateTime();
            $created->setTimestamp(hexdec(substr($card['id'], 0, 8)));

            $data[$created->getTimestamp()] = $created->format('Y-m-d H:i:s').' '.$cardName;
        }

        sort($data);
        return $data;
    }
}
