<?php

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
     * @param array<mixed> $cards The cards to be sorted
     *
     * @return array<mixed>
     */
    public function sort(array $cards);
}
