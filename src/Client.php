<?php

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
     * GuzzleHttp\Client client
     */
    protected \GuzzleHttp\Client $httpClient;

    /**
     * Trello Singleton
     */
    protected static ?Client $instance = null;

    /**
     * Constructor
     */
    public function __construct(?\GuzzleHttp\Client $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Singleton for the Trello Client
     *
     * @param Config $config The configuration we need to spike the client
     *
     * @return Client
     */
    public static function instance(?Config $config = null): Client
    {
        if (self::$instance == null) {
            $httpClient = new HttpClient([
                'base_uri' => 'https://api.trello.com',
                'query' => [
                    'key' => $config->getKey(),
                    'token' => $config->getSecret()
                ]
            ]);
            self::$instance = new self($httpClient);
        }

        return self::$instance;
    }

    /**
     * Reset the singleton
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }

    /**
     * Getter for the HTTPClient
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->httpClient;
    }

    /**
     * Getter for the Boards
     *
     * @return array<int,mixed>
     */
    public function getBoards(): array
    {
        $response = $this->httpClient->request('GET', '/1/members/me/boards');
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Getter for a board, given a name
     *
     * @param string $name The name of the board we want
     *
     * @return array<mixed>
     */
    public function getBoardByName($name): ?array
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
     * @return array<mixed>
     */
    public function getCards($boardId): array
    {
        $response = $this->httpClient->request('GET', '/1/boards/' . $boardId . '/cards');
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Getter for the checklists from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array<mixed>
     */
    public function getCardChecklist($cardId): array
    {
        $response = $this->httpClient->request('GET', '/1/cards/' . $cardId . '/checklists');
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Getter for the actions from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array<mixed>
     */
    public function getCardActions($cardId): array
    {
        $response = $this->httpClient->request('GET', '/1/cards/' . $cardId . '/actions');
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Getter for the members from a given card
     *
     * @param int $cardId Id of the card
     *
     * @return array<mixed>
     */
    public function getCardMembers($cardId): array
    {
        $response = $this->httpClient->request('GET', '/1/cards/' . $cardId . '/members');
        return json_decode((string) $response->getBody(), true);
    }


    /**
     * Getter for the lists from a given board
     *
     * @param int $boardId Id of the board which the lists are defined
     *
     * @return array<mixed>
     */
    public function getLists($boardId): array
    {
        $response = $this->httpClient->request('GET', '/1/boards/' . $boardId . '/lists');
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Getter for member information
     *
     * @param int $memberId The member id
     *
     * @return array<mixed>
     */
    public function getMember($memberId): array
    {
        $response = $this->httpClient->request('GET', '/1/members/' . $memberId);
        return json_decode((string) $response->getBody(), true);
    }
}
