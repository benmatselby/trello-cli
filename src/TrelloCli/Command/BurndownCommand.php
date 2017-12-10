<?php
/**
 * Give me some column stats for the burndown
 */

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
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('board:burndown')
            ->setDescription('Get some stats for each column on the board')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name');
    }

    /**
     * Get some stats for each column on the board
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lists = [];
        $cardCount = 0;
        $pointCount = 0;

        $boardName = $input->getArgument('board-name');

        $client = Client::instance();
        $board = $client->getBoardByName($boardName);
        $boardLists = $client->getLists($board['id']);

        foreach ($boardLists as $boardList) {
            $lists[$boardList['id']]['name'] = $boardList['name'];
            $lists[$boardList['id']]['count'] = 0;
            $lists[$boardList['id']]['points'] = 0;
        }

        $cards = $client->getCards($board['id']);

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
    }
}
