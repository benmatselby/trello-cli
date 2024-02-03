<?php

namespace TrelloCli\Test\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Console\Tester\CommandTester;
use TrelloCli\Command\ListBoardsCommand;

#[CoversClass(ListBoardsCommand::class)]
class ListBoardsCommandTest extends \PHPUnit\Framework\TestCase
{
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

    public function testExecuteCanRenderWhatWeWant(): void
    {
        $client = $this->createMock('\TrelloCli\Client');
        $client
            ->method('getBoards')
            ->willReturn([['name' => 'Films', 'closed' => false]]);

        $tester = new CommandTester(new ListBoardsCommand($client));
        $tester->execute([]);

        $this->assertEquals("Films", trim($tester->getDisplay()));
    }
}
