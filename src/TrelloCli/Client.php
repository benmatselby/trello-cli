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
     * GuzzleHttp\Client client
     */
    protected $httpClient;

    /**
     * Trello Singleton
     */
    protected static $instance;

    /**
     * Constructor
     */
    public function __construct($httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Singleton for the Trello Client
     *
     * @param array $config The configuration we need to spike the client
     *
     * @return Client
     */
    public static function instance(array $config = [])
    {
        if (self::$instance == null) {
            $httpClient = new HttpClient([
                'base_uri' => 'https://api.trello.com',
                'query' => [
                    'key' => $config['key'],
                    'token' => $config['secret']
                ]
            ]);
            self::$instance = new self($httpClient);
        }

        return self::$instance;
    }

    /**
     * Reset the singleton
     */
    public static function resetInstance()
    {
        self::$instance = null;
    }

    /**
     * Getter for the HTTPClient
     *
     * @return \GuzzleHttp\Client
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
        $response = $this->httpClient->get('/1/members/me/boards');
        return json_decode($response->getBody(), true);
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
        $response = $this->httpClient->get('/1/boards/' . $boardId . '/cards');
        return json_decode($response->getBody(), true);
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
        return json_decode($response->getBody(), true);
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
        return json_decode($response->getBody(), true);
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
        return json_decode($response->getBody(), true);
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
        $response = $this->httpClient->get('/1/boards/' . $boardId . '/lists');
        return json_decode($response->getBody(), true);
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
        $response = $this->httpClient->get('/1/members/' . $memberId);
        return json_decode($response->getBody(), true);
    }
}
