<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\ListBoardsCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\ListBoardsCommand
 */
class ListBoardsCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\ListBoardsCommand::configure
     */
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new ListBoardsCommand($trello);

        $this->assertEquals($command->getName(), 'board:list');
        $this->assertEquals(
            $command->getDescription(),
            'List all the boards you have access to'
        );
    }
}
