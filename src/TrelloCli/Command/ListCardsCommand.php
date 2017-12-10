<?php
/**
 * List cards command
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;
use TrelloCli\Filter\Label;
use TrelloCli\Filter\IgnoreLabel;
use TrelloCli\Sorter\DateCreated;

/**
 * List cards command
 *
 * Responsible for interfacing with the Trello API to get a list of cards
 */
class ListCardsCommand extends Command
{
    /**
     * Array of sorters we can use
     *
     * @var array
     */
    protected $sorters = [
        'created'
    ];

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('cards')
            ->setDescription('List all cards for a board')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name')
            ->addOption(
                'strip-scrum-for-trello',
                's',
                InputOption::VALUE_NONE,
                'Do we strip () from the start of the name'
            )
            ->addOption(
                'ignore-labels',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'List of tags to ignore',
                []
            )
            ->addOption(
                'filter-labels',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'List of tags to filter on',
                []
            )
            ->addOption(
                'sort',
                null,
                InputOption::VALUE_OPTIONAL,
                'Options include ('.implode(', ',$this->sorters).')'
            );
    }

    /**
     * List the cards for a given board
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');
        $stripStoryPoints = $input->getOption('strip-scrum-for-trello');
        $ignoreLabels = $input->getOption('ignore-labels');
        $filterLabels = $input->getOption('filter-labels');
        $sortMethod = $input->getOption('sort');

        $client = Client::instance();
        $board = $client->getBoardByName($boardName);
        $lists = $client->getLists($board['id']);
        $cards = $client->getCards($board['id']);

        switch ($sortMethod) {
            case 'created':
                $sorter = new DateCreated();
                $cards = $sorter->sort($cards);

        }

        if (!empty($ignoreLabels)) {
            $filter = new IgnoreLabel();
            $cards = $filter->setCriteria($ignoreLabels)->filter($cards);
        }

        if (!empty($filterLabels)) {
            $filter = new Label();
            $cards = $filter->setCriteria($filterLabels)->filter($cards);
        }

        $boardLayout = [];

        foreach ($lists as $list) {
            $boardLayout[$list['id']] = [
                'name' => $list['name'],
                'cards' => []
            ];
        }

        foreach ($cards as $card) {
            $cardName = $card['name'];

            if (!empty($card['badges']['due'])) {
                $due = new \DateTime($card['badges']['due']);
                $cardName = $cardName . ' (Due: ' . $due->format('d-m-Y H:i:s') . ')';
            }

            if ($stripStoryPoints) {
                $cardName = preg_replace('/^\(.+?\)/', '', $cardName);
            }

            $boardLayout[$card['idList']]['cards'][] = trim($cardName);
        }

        $boardName = $boardName . ' (' . count($cards) . ')' ;
        $output->writeln($boardName);
        $output->writeln(str_repeat("=", strlen($boardName)) . PHP_EOL);

        foreach ($boardLayout as $layout) {
            $listName = $layout['name'] . ' (' . count($layout['cards']) . ')';
            $output->writeln($listName);
            $output->writeln(str_repeat("=", strlen($listName)) . PHP_EOL);

            foreach ($layout['cards'] as $layoutCard) {
                $output->writeln('* ' . $layoutCard);
            }
            $output->writeln('' . PHP_EOL);
        }
    }
}
