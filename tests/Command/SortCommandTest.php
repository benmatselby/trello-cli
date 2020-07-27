<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\SortCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\SortCommand
 */
class SortCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\SortCommand::configure
     */
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new SortCommand($trello);

        $this->assertEquals($command->getName(), 'cards:sort');
        $this->assertEquals(
            $command->getDescription(),
            'List the cards in a certain order'
        );
    }
}
