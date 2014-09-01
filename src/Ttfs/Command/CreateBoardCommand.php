<?php
/**
 * Create a board in trello
 */

namespace Ttfs\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a board in trello
 *
 * Responsible for taking a txt file from [location] which is in
 * the format exported from TFS web and then creating a board
 */
class CreateBoardCommand extends \Cilex\Command\Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('create')
            ->setDescription('Create a board based on a copy/pasted output from TFS Web. Save the output in a tab (default) separated text file. It must have at least ID, Story Points, Work Item Type and Title defined in the output')
            ->addArgument('name', InputArgument::REQUIRED, 'The board name')
            ->addArgument('file', InputArgument::OPTIONAL, 'The text file location', '/tmp/tfs.txt');
    }

    /**
     * Execute the board/list/card creation
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new \Ttfs\Client($this->getContainer());
        $http = $client->getHttpClient();

        $location = $input->getArgument('file');

        if (!file_exists($location)) {
            throw new \InvalidArgumentException($location . ' does not exist');
        }

        $tfs = explode(PHP_EOL, file_get_contents($location));
        $header = explode("\t", array_shift($tfs));

        // Setup the keys for the data
        $idIdx = array_search('ID', $header);
        $titleIdx = array_search('Title', $header);
        $typeIdx = array_search('Work Item Type', $header);
        $storyPointsIdx = array_search('Story Points', $header);

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
        foreach ($tfs as $line) {
            $parts = explode("\t", $line);

            if ($parts[$typeIdx] === 'Requirement') {
                $cardResult = $http->post('/1/cards', [
                    'body' => [
                        'name' => '(' . $parts[$storyPointsIdx] . ')' . $parts[$idIdx] . ' - ' . $parts[$titleIdx],
                        'idList' => $listId,
                        'urlSource' => null
                    ]
                ]);

                $output->writeln('Card ID: ' . $cardResult->json()['id']);
            }
        }
    }
}
