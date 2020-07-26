<?php

/**
 * Filter cards out based on labels
 */

namespace TrelloCli\Filter;

use TrelloCli\Filter;

/**
 * Responsible for filtering out cards based on their labels
 */
class Label implements Filter
{
    /**
     * The filter criteria which in this case is a bunch of
     * trello labels to ignore
     *
     * @var array<string>
     */
    protected array $criteria;

    /**
     * Set the filter criteria
     *
     * @param mixed $criteria
     *
     * @return Label
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function filter(array $cards): array
    {
        $data = [];
        foreach ($cards as $card) {
            $addCard = false;
            foreach ($card['labels'] as $cardLabel) {
                if (in_array($cardLabel['name'], $this->criteria)) {
                    $addCard = true;
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
