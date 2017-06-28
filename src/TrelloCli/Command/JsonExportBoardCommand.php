<?php
/**
 * Json board exporter command
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;

/**
 * Json board exporter command
 *
 * Responsible for interfacing with the Trello API to get a JSON export of a board
 */
class JsonExportBoardCommand extends Command
{
    /**
     * The Trello Client
     *
     * @var \TrelloCli\Client
     */
    protected $client;

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('board-export-json')
            ->setDescription('List all cards for a board')
            ->addOption(
                'board',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'The board you want to export'
            );
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
        $boardNames = $input->getOption('board');

        $this->client = Client::instance();
        $boards = [];

        foreach ($boardNames as $boardName) {
            $boards[] = $this->buildBoard($boardName);
        }

        $output->writeln(json_encode($boards));
    }

    /**
     * Build the data on the board
     *
     * @param string $boardName The board name of the board we want to collate data for
     *
     * @return array
     */
    protected function buildBoard($boardName)
    {
        $board = $this->client->getBoardByName($boardName);

        $boardLayout = [
            'name' => $boardName
        ];

        $boardLists = [];
        $lists = $this->client->getLists($board['id']);
        foreach ($lists as $list) {
            $boardLists[$list['id']] = [
                'name' => $list['name'],
                'cards' => []
            ];
        }

        $cards = $this->client->getCards($board['id']);

        foreach ($cards as $card) {
            $card['checklists'] = $this->client->getCardChecklist($card['id']);
            $card['actions'] = $this->client->getCardActions($card['id']);
            $card['members'] = $this->client->getCardMembers($card['id']);
            $boardLists[$card['idList']]['cards'][] = $card;
        }

        $boardLayout['lists'] = $boardLists;

        return $boardLayout;
    }
}
