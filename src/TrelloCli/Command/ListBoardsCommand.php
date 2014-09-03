<?php
/**
 * List boards command
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * List Boards command
 *
 * Responsible for interfacing with the Trello API to get a list of boards
 */
class ListBoardsCommand extends \Cilex\Command\Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('boards')
            ->setDescription('List all boards for user')
            ->addOption('cards', 'c', InputOption::VALUE_NONE, 'The board name');
    }

    /**
     * List the boards available to user
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $showCards = $input->getOption('cards');

        $client = new \TrelloCli\Client($this->getContainer());
        $http = $client->getHttpClient();

        $boardsResponse = $http->get('/1/members/me/boards');
        $boards = $boardsResponse->json();

        $output->writeln('Boards found: ' . count($boards) . PHP_EOL);

        foreach ($boards as $board) {
            $output->writeln($board['name'] . ($board['closed'] ? ' [Closed]' : ''));

            if ($showCards == true) {
                $cardsResponse = $http->get('/1/boards/' . $board['id'] . '/cards');
                $cards = $cardsResponse->json();

                foreach ($cards as $card) {
                    $output->writeln(' ' . $card['name']);
                }
            }
        }
    }
}
