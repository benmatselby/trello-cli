<?php

namespace TrelloCli\Test\Filter;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Filter\Label;

#[CoversClass(Label::class)]
class LabelTest extends \PHPUnit\Framework\TestCase
{
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
