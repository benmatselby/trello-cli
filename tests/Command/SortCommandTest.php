<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\SortCommand;

#[CoversClass(SortCommand::class)]
class SortCommandTest extends \PHPUnit\Framework\TestCase
{
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
