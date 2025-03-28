<?php

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;
use TrelloCli\Sorter\DateCreated;

/**
 * Responsible for interfacing with the Trello API to get a list of cards and
 * then sorting them in a certain way
 */
class SortCommand extends Command
{
    /**
     * The TrelloCLI Client
     */
    private Client $client;

    /**
     * Array of sorters we can use
     *
     * @var array<string>
     */
    protected array $sorters = [
        'created'
    ];

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
        $sorters = implode(', ', $this->sorters);
        $this
            ->setName('cards:sort')
            ->setDescription('List the cards in a certain order')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name')
            ->addOption(
                'by',
                null,
                InputOption::VALUE_REQUIRED,
                'Options include (' . $sorters . ')'
            );
    }

    /**
     * Sort the cards for a given board
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $boardName = $input->getArgument('board-name');
        $sortMethod = $input->getOption('by');

        if (!in_array($sortMethod, $this->sorters)) {
            throw new \InvalidArgumentException('Please use a valid sorter: ' . implode($this->sorters));
        }

        switch ($sortMethod) {
            case 'created':
            default:
                $sorter = new DateCreated();
        }

        $board = $this->client->getBoardByName($boardName);
        $cards = $this->client->getCards($board['id']);
        $cards = $sorter->sort($cards);

        foreach ($cards as $card) {
            $output->writeln('* ' . $card['name']);
        }

        return Command::SUCCESS;
    }
}
