<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\JsonExportBoardCommand;

#[CoversClass(JsonExportBoardCommand::class)]
class JsonExportBoardCommandTest extends \PHPUnit\Framework\TestCase
{
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
