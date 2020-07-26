<?php

/**
 * Ignore Label Filter Test File
 */

namespace TrelloCli\Test\Filter;

use TrelloCli\Filter\IgnoreLabel;

/**
 * Responsible for testing \TrelloCli\Filter\IgnoreLabel
 */
class IgnoreLabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Filter\IgnoreLabel::setCriteria
     * @covers \TrelloCli\Filter\IgnoreLabel::filter
     */
    public function testWeCanFilterCardsIfTheyHaveACertainLabel()
    {
        $cards = [
            [
                'name' => 'Card 1',
                'labels' => [
                    ['name' => 'label-one']
                ]
            ],
            [
                'name' => 'Card 2',
                'labels' => [
                    ['name' => 'label-two']
                ]
            ],
            [
                'name' => 'Card 3',
                'labels' => [
                    ['name' => 'label-two']
                ]
            ],
            [
                'name' => 'Card 4',
                'labels' => [
                    ['name' => 'label-one']
                ]
            ],
        ];

        $expected = [
            [
                'name' => 'Card 2',
                'labels' => [
                    ['name' => 'label-two']
                ],
            ],
            [
                'name' => 'Card 3',
                'labels' => [
                    ['name' => 'label-two']
                ]
            ],
        ];

        $filter = new IgnoreLabel();
        $filter->setCriteria(['label-one']);
        $actual = $filter->filter($cards);

        $this->assertEquals($expected, $actual);
    }
}
