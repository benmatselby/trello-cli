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
     * Getter for a board, given a name
     *
     * @param string $name The name of the board we want
     *
     * @return array|null
     */
    public function getBoardByName($name)
    {
        $boards = $this->getBoards();

        foreach ($boards as $board) {

            if (strtolower($board['name']) == strtolower($name)) {
                return $board;
            }
        }

        return null;
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
     * Getter for the checklists from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array
     */
    public function getCardChecklist($cardId)
    {
        $response = $this->httpClient->get('/1/cards/' . $cardId . '/checklists');
        return $response->json();
    }

    /**
     * Getter for the actions from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array
     */
    public function getCardActions($cardId)
    {
        $response = $this->httpClient->get('/1/cards/' . $cardId . '/actions');
        return $response->json();
    }

    /**
     * Getter for the members from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array
     */
    public function getCardMembers($cardId)
    {
        $response = $this->httpClient->get('/1/cards/' . $cardId . '/members');
        return $response->json();
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
