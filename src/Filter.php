<?php

namespace TrelloCli;

/**
 * Responsible for defining the interface for filtering
 */
interface Filter
{
    /**
     * Filter cards based on some criteria
     *
     * @param array<mixed> $cards The cards from the Trello API
     *
     * @return array<mixed>
     */
    public function filter(array $cards): array;
}
