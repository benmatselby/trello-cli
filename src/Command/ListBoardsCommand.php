<?php

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;

/**
 * List Boards command
 *
 * Responsible for interfacing with the Trello API to get a list of boards
 */
class ListBoardsCommand extends Command
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
            ->setName('board:list')
            ->setDescription('List all the boards you have access to')
            ->addOption('cards', 'c', InputOption::VALUE_NONE, 'The board name')
            ->addOption('hide-closed', 's', InputOption::VALUE_NONE, 'Do we show closed boards')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Show debug information');
    }

    /**
     * List the boards available to user
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $showCards = $input->getOption('cards');
        $debug = $input->getOption('debug');
        $hideClosed = $input->getOption('hide-closed');

        $boards = $this->client->getBoards();

        foreach ($boards as $board) {
            $name = $board['name'];

            if ($board['closed']) {
                $name =  'Closed - ' . $name;
                if ($hideClosed) {
                    continue;
                }
            }

            $output->writeln($name);

            if ($debug) {
                $output->writeln(var_export($board, true));
            }

            if ($showCards == true) {
                $cards = $this->client->getCards($board['id']);

                foreach ($cards as $card) {
                    $output->writeln(' ' . $card['name']);

                    if ($debug) {
                        $output->writeln(var_export($card, true));
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
