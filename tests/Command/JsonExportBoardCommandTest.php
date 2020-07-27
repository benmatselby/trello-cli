<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\JsonExportBoardCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\JsonExportBoardCommand
 */
class JsonExportBoardCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\JsonExportBoardCommand::configure
     */
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new JsonExportBoardCommand($trello);

        $this->assertEquals($command->getName(), 'board:export-json');
        $this->assertEquals(
            $command->getDescription(),
            'Export the cards from the board'
        );
    }
}
