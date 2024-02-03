<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\ListCardsCommand;

#[CoversClass(ListCardsCommand::class)]
class ListCardsCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new ListCardsCommand($trello);

        $this->assertEquals($command->getName(), 'cards:list');
        $this->assertEquals(
            $command->getDescription(),
            'List all cards for a board'
        );
    }
}
