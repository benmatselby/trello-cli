<?php
/**
 * List all the cards by label for a given board
 */

namespace TrelloCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TrelloCli\Client;

/**
 * List all the cards by label for a given board
 *
 * Responsible for interfacing with the Trello API to get a list of cards and display by the label
 */
class LabelCardsCommand extends Command
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('cards:labels')
            ->setDescription('List all cards for a board via label')
            ->addArgument('board-name', InputArgument::REQUIRED, 'The board name');
    }

    /**
     * List the cards for a given board
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boardName = $input->getArgument('board-name');

        $client = Client::instance();
        $board = $client->getBoardByName($boardName);

        foreach ($board['labelNames'] as $color => $label) {
            if ($label == '') {
                $label = 'Unknown';
            }

            $boardLayout[$color] = [
                'name' => $label,
                'cards' => []
            ];
        }

        $cards = $client->getCards($board['id']);

        $output->writeln($boardName . ' (' . count($cards) . ')' . PHP_EOL);
        foreach ($cards as $card) {
            $cardName = $card['name'];
            foreach ($card['labels'] as $cardLabel) {
                $boardLayout[$cardLabel['color']]['cards'][] = trim($cardName);
            }
        }

        foreach ($boardLayout as $layout) {
            $output->writeln($layout['name'] . ' (' . count($layout['cards']) . ')');
            foreach ($layout['cards'] as $layoutCard) {
                $output->writeln(' ' . $layoutCard);
            }
            $output->writeln('');
        }

        return 0;
    }
}
