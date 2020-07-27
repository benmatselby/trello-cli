<?php

namespace TrelloCli\Test\Sorter;

use TrelloCli\Sorter\DateCreated;

/**
 * Responsible for testing \TrelloCli\Sorter\DateCreated
 */
class DateCreatedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Sorter\DateCreated::sort
     */
    public function testWeOrderByDateCreated(): void
    {
        $cards = [
            [
                'id' => '5a21685d-xxxxxxx',
                'name' => 'Card 1',
            ],
            [
                'id' => '5a21686d-yyyyyyy',
                'name' => 'Card 2',
            ],
            [
                'id' => '5a08ebfc-bbbbbbb',
                'name' => 'Card 3',
            ],
            [
                'id' => '5a211dd8-ooooooo',
                'name' => 'Card 4',
            ],
        ];

        $expected = [
            [
                'id' => '5a08ebfc-bbbbbbb',
                'name' => '2017-11-13 00:49:00 Card 3',
            ],
            [
                'id' => '5a211dd8-ooooooo',
                'name' => '2017-12-01 09:16:08 Card 4',
            ],
            [
                'id' => '5a21685d-xxxxxxx',
                'name' => '2017-12-01 14:34:05 Card 1',
            ],
            [
                'id' => '5a21686d-yyyyyyy',
                'name' => '2017-12-01 14:34:21 Card 2',
            ],
        ];

        $sorter = new DateCreated();
        $actual = $sorter->sort($cards);

        $this->assertEquals($expected, $actual);
    }
}
