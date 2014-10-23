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
            ->addOption('strip-scrum-for-trello', 's', InputOption::VALUE_NONE, 'Do we strip () from the start of the name')
            ->addOption('ignore-tags', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'List of tags to ignore', []);
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
        $ignoreTags = $input->getOption('ignore-tags');

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

        $output->writeln($boardName . ' (' . count($cards) . ')' . PHP_EOL);
        foreach ($cards as $card) {

            $addCard = true;
            if (!empty($ignoreTags)) {
                foreach ($card['labels'] as $cardLabel) {
                    if (in_array($cardLabel['name'], $ignoreTags)) {
                        $addCard = false;
                        break;
                    }
                }
            }

            if (!$addCard) {
                continue;
            }

            $cardName = $card['name'];

            if ($stripStoryPoints) {
                $cardName = preg_replace('/^\(.+?\)/', '', $cardName);
            }

            $boardLayout[$card['idList']]['cards'][] = trim($cardName);
        }

        foreach ($boardLayout as $layout) {

            $output->writeln($layout['name'] . ' (' . count($layout['cards']) . ')');
            foreach ($layout['cards'] as $layoutCard) {
                $output->writeln(' ' . $layoutCard);
            }
            $output->writeln('');
        }
    }
}
