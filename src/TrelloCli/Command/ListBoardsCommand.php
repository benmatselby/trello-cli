<?php
/**
 * List boards command
 */

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
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('boards')
            ->setDescription('List all boards for user')
            ->addOption('cards', 'c', InputOption::VALUE_NONE, 'The board name')
            ->addOption('hide-closed', 's', InputOption::VALUE_NONE, 'Do we show closed boards')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Show debug information');
    }

    /**
     * List the boards available to user
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $showCards = $input->getOption('cards');
        $debug = $input->getOption('debug');
        $hideClosed = $input->getOption('hide-closed');

        $client = Client::instance();
        $boards = $client->getBoards();

        foreach ($boards as $board) {
            $name = $board['name'];

            if ($board['closed']) {
                $name =  'Closed - '.$name;
                if ($hideClosed) {
                    continue;
                }
            }

            $output->writeln($name);

            if ($debug) {
                $output->writeln(var_export($board, 1));
            }

            if ($showCards == true) {
                $cards = $client->getCards($board['id']);

                foreach ($cards as $card) {
                    $output->writeln(' ' . $card['name']);

                    if ($debug) {
                        $output->writeln(var_export($card, 1));
                    }
                }
            }
        }
    }
}
