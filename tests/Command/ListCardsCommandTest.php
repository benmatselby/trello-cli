<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\ListCardsCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\ListCardsCommand
 */
class ListCardsCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\ListCardsCommand::configure
     */
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
