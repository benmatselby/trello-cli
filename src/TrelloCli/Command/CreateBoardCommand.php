<?php
/**
 * Create a board in trello
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;

/**
 * Create a board in trello
 *
 * Responsible for taking a txt file from [location] which is in a tab|csv
 * format and then creating a board
 */
class CreateBoardCommand extends Command
{
    const FORMAT_CSV = 'csv';
    const FORMAT_TAB = 'tab';

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('board:create')
            ->setDescription(
                'Create a board based on a file in csv|tab format. Format should be: ID, Story Points, Title, Description'
            )
            ->addArgument('name', InputArgument::REQUIRED, 'The board name')
            ->addArgument('file', InputArgument::OPTIONAL, 'The text file location', '/tmp/trello.txt')
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Format of the input (tab|csv)', self::FORMAT_CSV);
    }

    /**
     * Execute the board/list/card creation
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = Client::instance();
        $http = $client->getHttpClient();

        $location = $input->getArgument('file');
        $format = $input->getOption('format');

        if ($format != self::FORMAT_CSV && $format != self::FORMAT_TAB) {
            throw new \InvalidArgumentException($format . ' is not a valid format');
        }

        if (!file_exists($location)) {
            throw new \InvalidArgumentException($location . ' does not exist');
        }

        $fileContents = explode(PHP_EOL, file_get_contents($location));

        if ($format == self::FORMAT_TAB) {
            $header = explode("\t", array_shift($fileContents));
        } else {
            $header = explode(",", array_shift($fileContents));
        }

        // Setup the keys for the data
        $idIdx = array_search('ID', $header);
        $titleIdx = array_search('Title', $header);
        $storyPointsIdx = array_search('Story Points', $header);
        $descIdx = array_search('Description', $header);

        // Create the board
        $boardResult = $http->post('/1/boards', [
            'body' => [
                'name' => $input->getArgument('name')
            ]
        ]);

        $boardId = $boardResult->json()['id'];
        $output->writeln('Board ID: ' . $boardId);

        // Create the list called Sprint Backlog for now
        $listResult = $http->post('/1/lists', [
            'body' => [
                'name' => 'Sprint Backlog',
                'idBoard' => $boardId
            ]
        ]);

        $listId = $listResult->json()['id'];
        $output->writeln('List ID: ' . $listId);

        // Create the cards
        foreach ($fileContents as $line) {
            $parts = explode("\t", $line);

            $cardResult = $http->post('/1/cards', [
                'body' => [
                    'name' => '(' . $parts[$storyPointsIdx] . ')' . $parts[$idIdx] . ' - ' . $parts[$titleIdx],
                    'desc' => $parts[$descIdx],
                    'idList' => $listId,
                    'urlSource' => null
                ]
            ]);

            $output->writeln('Card ID: ' . $cardResult->json()['id']);
        }

        return 0;
    }
}
