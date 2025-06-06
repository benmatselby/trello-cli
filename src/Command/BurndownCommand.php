<?php

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use TrelloCli\Client;

/**
 * Burndown command
 *
 * Responsible for interfacing with the Trello API to get a some stats for the columns
 */
class BurndownCommand extends Command
{
    /**
     * The TrelloCLI Client
     */
    private Client $client;

    /**
     * Constructor for the command
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    /**
     * Configure the command
     */
    protected function configure(): void
    {
        $this
            ->setName('board:burndown')
            ->setDescription('Get some stats for each column on the board')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name');
    }

    /**
     * Get some stats for each column on the board
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lists = [];
        $cardCount = 0;
        $pointCount = 0;

        $boardName = $input->getArgument('board-name');

        $board = $this->client->getBoardByName($boardName);
        if ($board == null) {
            $output->writeln("Cannot find board " . $boardName);
            return 1;
        }
        $boardLists = $this->client->getLists($board['id']);

        foreach ($boardLists as $boardList) {
            $lists[$boardList['id']]['name'] = $boardList['name'];
            $lists[$boardList['id']]['count'] = 0;
            $lists[$boardList['id']]['points'] = 0;
        }

        $cards = $this->client->getCards($board['id']);

        foreach ($cards as $card) {
            $lists[$card['idList']]['count'] += 1;

            preg_match('/^\((.+?)\)/', $card['name'], $points);
            if (!empty($points) && isset($points[1])) {
                $lists[$card['idList']]['points'] += $points[1];
                $pointCount += $points[1];
            }

            $cardCount++;
        }

        $lists[] = new TableSeparator();
        $lists[] = ['Total', $cardCount, $pointCount];
        $table = new Table($output);
        $table
            ->setHeaders(array('List', 'Cards', 'Story Points'))
            ->setRows($lists);
        $table->render();

        return Command::SUCCESS;
    }
}
