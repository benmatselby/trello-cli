<?php

/**
 * Order interface
 */

namespace TrelloCli;

/**
 * Responsible for defining the interface on order logic
 */
interface Sorter
{
    /**
     * Sort the cards in a given way and then return the data back
     * in the same data structure
     *
     * @param array $cards The cards from the Trello API
     *
     * @return array
     */
    public function sort(array $cards);
}
