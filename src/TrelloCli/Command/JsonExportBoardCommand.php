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
    protected function configure(): void
    {
        $this
            ->setName('board:export-json')
            ->setDescription('Export the cards from the board')
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
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $boardNames = $input->getOption('board');

        $this->client = Client::instance();
        $boards = [];

        foreach ($boardNames as $boardName) {
            $boards[] = $this->buildBoard($boardName);
        }

        $output->writeln(json_encode($boards));

        return 0;
    }

    /**
     * Build the data on the board
     *
     * @param string $boardName The board name of the board we want to collate data for
     *
     * @return array<string, array<int|string, array<string, mixed>>|string>
     */
    protected function buildBoard($boardName): array
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
