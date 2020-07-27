<?php

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;

/**
 * List people command
 *
 * Responsible for interfacing with the Trello API to get a list of people and
 * displaying all the cards they have
 */
class ListPeopleCommand extends Command
{
    /**
     * The TrelloCLI Client
     */
    private Client $client;

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
        $this
            ->setName('board:people')
            ->setDescription('List the people on a board and the cards they have assigned to them')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name')
            ->addOption('hide-cards', 's', InputOption::VALUE_NONE, 'Hide the cards, to give just an overview')
            ->addOption('show-unassigned', 'u', InputOption::VALUE_NONE, 'Show people who have nothing assigned');
    }

    /**
     * List the people and the cards they have for a given board
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');
        $hideCards = $input->getOption('hide-cards');
        $showUnassigned = $input->getOption('show-unassigned');

        $board = $this->client->getBoardByName($boardName);

        $members = [];
        $lists = [];
        $boardLists = $this->client->getLists($board['id']);

        foreach ($boardLists as $boardList) {
            $lists[$boardList['id']] = $boardList['name'];
        }

        foreach ($board['memberships'] as $membership) {
            $member = $this->client->getMember($membership['idMember']);
            $members[$member['id']]['name'] = $member['fullName'];
            $members[$member['id']]['cards'] = [];
            $members[$member['id']]['cardCount'] = 0;
            $members[$member['id']]['storyPoints'] = 0;
        }

        $cards = $this->client->getCards($board['id']);

        foreach ($cards as $card) {
            foreach ($card['idMembers'] as $cardMemberId) {
                $members[$cardMemberId]['cards'][$card['idList']][] = $card;
                $members[$cardMemberId]['cardCount'] = (int) $members[$cardMemberId]['cardCount'] + 1;

                preg_match('/^\((.+?)\)/', $card['name'], $points);

                if (!empty($points) && isset($points[1])) {
                    $members[$cardMemberId]['storyPoints'] =
                        (float) $members[$cardMemberId]['storyPoints'] + $points[1];
                }
            }
        }

        foreach ($members as $member) {
            $cardCount = $member['cardCount'];

            if ($cardCount == 0 && $showUnassigned == false) {
                continue;
            }
            $output->writeln($member['name']);
            $output->writeln(" Story Points [" . $member['storyPoints'] . "]");
            $output->writeln(" Cards [" . $cardCount . "]");

            foreach ($member['cards'] as $listId => $listCards) {
                $output->writeln(' ' . $lists[$listId] . ' [' . count($listCards) . ']');

                if (!$hideCards) {
                    foreach ($listCards as $c) {
                        $output->writeln('  ' . $c['name']);
                    }
                }
            }
            $output->writeln('');
        }

        return Command::SUCCESS;
    }
}
