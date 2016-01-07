<?php
/**
 * Json board exporter command
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Json board exporter command
 *
 * Responsible for interfacing with the Trello API to get a JSON export of a board
 */
class JsonExportBoardCommand extends \Cilex\Command\Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('board-export-json')
            ->setDescription('List all cards for a board')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name');
    }

    /**
     * Export the board as json
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');

        $client = new \TrelloCli\Client($this->getContainer());
        $board = $client->getBoardByName($boardName);

        $boardLayout = [];

        $lists = $client->getLists($board['id']);
        foreach ($lists as $list) {
            $boardLayout[$list['id']] = [
                'name' => $list['name'],
                'cards' => []
            ];
        }

        $cards = $client->getCards($board['id']);

        foreach ($cards as $card) {
            $boardLayout[$card['idList']]['cards'][] = $card;
        }

        $output->writeln(json_encode($boardLayout));
    }
}
