<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\BurndownCommand;

#[CoversClass(BurndownCommand::class)]
class BurndownCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new BurndownCommand($trello);

        $this->assertEquals($command->getName(), 'board:burndown');
        $this->assertEquals(
            $command->getDescription(),
            'Get some stats for each column on the board'
        );
    }
}
