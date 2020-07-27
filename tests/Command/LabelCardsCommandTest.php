<?php

namespace TrelloCli\Test\Command;

use TrelloCli\Command\LabelCardsCommand;
use TrelloCli\Client;

/**
 * Responsible for testing \TrelloCli\Command\LabelCardsCommand
 */
class LabelCardsCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\LabelCardsCommand::configure
     */
    public function testConfigure(): void
    {
        $trello = $this->createMock('\TrelloCli\Client');

        $command = new LabelCardsCommand($trello);

        $this->assertEquals($command->getName(), 'cards:labels');
        $this->assertEquals(
            $command->getDescription(),
            'List all cards for a board via label'
        );
    }
}
