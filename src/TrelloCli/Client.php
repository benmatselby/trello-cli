<?php
/**
 * Trello Client
 */

namespace TrelloCli;

use GuzzleHttp\Client as HttpClient;

/**
 * Minimal wrapper around Guzzle http client
 *
 * Responsible for bootstrapping the http client
 */
class Client
{
    /**
     * Cilex application
     */
    protected $container;

    /**
     * GuzzleHttp client
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param CilexApplication $container Application container
     */
    public function __construct($container, $httpClient = null)
    {
        $this->container = $container;

        if ($this->httpClient === null) {

            $httpClient = new HttpClient([
                'base_url' => 'https://api.trello.com/',
                'defaults' => [
                    'query' => [
                        'key' => $this->container['trello.cli.config']['trello']['key'],
                        'token' => $this->container['trello.cli.config']['trello']['secret']
                    ]
                ]
            ]);
        }

        $this->httpClient = $httpClient;
    }

    /**
     * Getter for the Boards
     *
     * @return array
     */
    public function getBoards()
    {
        $boardsResponse = $this->httpClient->get('/1/members/me/boards');
        $boards = $boardsResponse->json();

        return $boards;
    }

    /**
     * Getter for the cards
     *
     * @param  int $boardId Id of the board which the cards are on
     *
     * @return array
     */
    public function getCards($boardId)
    {
        $cardsResponse = $this->httpClient->get('/1/boards/' . $boardId . '/cards');
        $cards = $cardsResponse->json();

        return $cards;
    }
}
