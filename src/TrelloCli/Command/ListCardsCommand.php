<?php
/**
 * List cards command
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * List cards command
 *
 * Responsible for interfacing with the Trello API to get a list of cards
 */
class ListCardsCommand extends \Cilex\Command\Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('cards')
            ->setDescription('List all cards for a board')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name')
            ->addOption('strip-scrum-for-trello', 's', InputOption::VALUE_NONE, 'Do we strip () from the start of the name');
    }

    /**
     * List the cards for a given board
     *
     * @param InputInterface  $input  The input from the user
     * @param OutputInterface $output The outputting interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');
        $stripStoryPoints = $input->getOption('strip-scrum-for-trello');

        $client = new \TrelloCli\Client($this->getContainer());

        $boards = $client->getBoards();
        $output->writeln('Boards found: ' . count($boards));

        foreach ($boards as $board) {

            if (strtolower($board['name']) == strtolower($boardName)) {
                $output->writeln($boardName . PHP_EOL);

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
                    $cardName = $card['name'];

                    if ($stripStoryPoints) {
                        $cardName = preg_replace('/^\(.+\)/', '', $cardName);
                    }

                    $boardLayout[$card['idList']]['cards'][] = ltrim($cardName);
                }

                foreach ($boardLayout as $layout) {

                    $output->writeln($layout['name']);
                    foreach ($layout['cards'] as $layoutCard) {
                        $output->writeln(' ' . $layoutCard);
                    }
                    $output->writeln('');
                }

                break;
            }
        }
    }
}
