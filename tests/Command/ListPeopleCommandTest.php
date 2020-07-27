<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\ListPeopleCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\ListPeopleCommand
 */
class ListPeopleCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\ListPeopleCommand::configure
     */
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new ListPeopleCommand($trello);

        $this->assertEquals($command->getName(), 'board:people');
        $this->assertEquals(
            $command->getDescription(),
            'List the people on a board and the cards they have assigned to them'
        );
    }
}
