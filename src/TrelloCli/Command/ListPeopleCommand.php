<?php
/**
 * List the people on the board, and the cards they have assigned
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * List people command
 *
 * Responsible for interfacing with the Trello API to get a list of people and
 * displaying all the cards they have
 */
class ListPeopleCommand extends \Cilex\Command\Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('people')
            ->setDescription('List the people on a board and the cards they have assigned to them')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name')
            ->addOption('hide-cards', 's', InputOption::VALUE_NONE, 'Hide the cards, to give just an overview');
    }

    /**
     * List the people and the cards they have for a given board
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');
        $hideCards = $input->getOption('hide-cards');

        $client = new \TrelloCli\Client($this->getContainer());
        $boards = $client->getBoards();

        foreach ($boards as $board) {

            if (strtolower($board['name']) == strtolower($boardName)) {

                $members = [];
                $lists = [];
                $boardLists = $client->getLists($board['id']);

                foreach ($boardLists as $boardList) {
                    $lists[$boardList['id']] = $boardList['name'];
                }

                foreach ($board['memberships'] as $membership) {
                    $member = $client->getMember($membership['idMember']);
                    $members[$member['id']]['name'] = $member['fullName'];
                    $members[$member['id']]['cards'] = [];
                    $members[$member['id']]['cardCount'] = 0;
                }

                $cards = $client->getCards($board['id']);

                foreach ($cards as $card) {

                    foreach ($card['idMembers'] as $cardMemberId) {

                        $members[$cardMemberId]['cards'][$card['idList']][] = $card;
                        $members[$cardMemberId]['cardCount'] = (int) $members[$cardMemberId]['cardCount'] + 1;
                    }
                }

                foreach ($members as $member) {

                    $output->writeln($member['name'] . ' [' . $member['cardCount'] . ']');

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

                break;
            }
        }
    }
}
