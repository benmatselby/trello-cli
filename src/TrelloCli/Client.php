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

        if ($httpClient === null) {

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
     * Getter for the HTTPClient
     *
     * @return GuzzleHttp
     */
    public function getHttpClient()
    {
        return $this->httpClient;
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
     * Getter for the cards from a given board
     *
     * @param int $boardId Id of the board which the cards are on
     *
     * @return array
     */
    public function getCards($boardId)
    {
        $cardsResponse = $this->httpClient->get('/1/boards/' . $boardId . '/cards');
        $cards = $cardsResponse->json();

        return $cards;
    }

    /**
     * Getter for the lists from a given board
     *
     * @param int $boardId Id of the board which the lists are defined
     *
     * @return array
     */
    public function getLists($boardId)
    {
        $listsResponse = $this->httpClient->get('/1/boards/' . $boardId . '/lists');
        $lists = $listsResponse->json();

        return $lists;
    }

    /**
     * Getter for member information
     *
     * @param int $memberId The member id
     *
     * @return array
     */
    public function getMember($memberId)
    {
        $memberResponse = $this->httpClient->get('/1/members/' . $memberId);
        $member = $memberResponse->json();

        return $member;
    }
}
