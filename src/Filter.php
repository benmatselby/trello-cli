<?php

/**
 * Filter interface
 */

namespace TrelloCli;

/**
 * Responsible for defining the interface for filtering
 */
interface Filter
{
    /**
     * Filter cards based on some criteria
     *
     * @param array<int,array> $cards The cards from the Trello API
     *
     * @return array<int,array>
     */
    public function filter(array $cards): array;
}
