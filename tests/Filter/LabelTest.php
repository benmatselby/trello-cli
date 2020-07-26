<?php

/**
 * Label Filter Test File
 */

namespace TrelloCli\Test\Filter;

use TrelloCli\Filter\Label;

/**
 * Responsible for testing \TrelloCli\Filter\Label
 */
class LabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Filter\Label::setCriteria
     * @covers \TrelloCli\Filter\Label::filter
     */
    public function testWeCanFilterCardsIfTheyHaveACertainLabel(): void
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
                'name' => 'Card 1',
                'labels' => [
                    ['name' => 'label-one']
                ],
            ],
            [
                'name' => 'Card 4',
                'labels' => [
                    ['name' => 'label-one']
                ]
            ],
        ];

        $filter = new Label();
        $filter->setCriteria(['label-one']);
        $actual = $filter->filter($cards);

        $this->assertEquals($expected, $actual);
    }
}
