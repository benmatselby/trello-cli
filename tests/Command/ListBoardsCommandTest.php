<?php

namespace TrelloCli\Test\Command;

use Symfony\Component\Console\Tester\CommandTester;
use TrelloCli\Command\ListBoardsCommand;

/**
 * Responsible for testing \TrelloCli\Command\ListBoardsCommand
 */
class ListBoardsCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Command\ListBoardsCommand::configure
     */
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

    /**
     * @covers \TrelloCli\Command\ListBoardsCommand::execute
     */
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
