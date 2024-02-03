<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Command\LabelCardsCommand;

#[CoversClass(LabelCardsCommand::class)]
class LabelCardsCommandTest extends \PHPUnit\Framework\TestCase
{
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
