<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\ListPeopleCommand;

#[CoversClass(ListPeopleCommand::class)]
class ListPeopleCommandTest extends \PHPUnit\Framework\TestCase
{
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
