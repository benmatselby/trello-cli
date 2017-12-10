<?php
/**
 * Filter cards out based on labels
 */

namespace TrelloCli\Filter;

use TrelloCli\Filter;

/**
 * Responsible for filtering out cards based on their labels
 */
class IgnoreLabel implements Filter
{
    /**
     * The filter criteria which in this case is a bunch of
     * trello labels to ignore
     *
     * @var array
     */
    protected $criteria;

    /**
     * Set the filter criteria
     *
     * @param mixed $criteria
     *
     * @return IgnoreLabel
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function filter(array $cards)
    {
        $data = [];
        foreach ($cards as $card) {
            $addCard = true;
            foreach ($card['labels'] as $cardLabel) {
                if (in_array($cardLabel['name'], $this->criteria)) {
                    $addCard = false;
                    break;
                }
            }

            if ($addCard) {
                $data[] = $card;
            }
        }
        return $data;
    }
}
