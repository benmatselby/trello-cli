<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\BurndownCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\BurndownCommand
 */
class BurndownCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\BurndownCommand::configure
     */
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
