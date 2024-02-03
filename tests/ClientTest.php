<?php

namespace TrelloCli\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\Attributes\DataProvider;
use TrelloCli\Client;
use TrelloCli\Config\Adapter;

#[CoversClass(Client::class)]
class ClientTest extends TestCase
{
    /**
     * @inheritdoc
     */
    public function tearDown(): void
    {
        Client::resetInstance();
    }

    /**
     * Wrapper to deal with the fact `getBody` needs a stream
     */
    protected function getResource(string $value): mixed
    {
        $resource = fopen('data://text/plain,' . $value, 'r');
        if ($resource === false) {
            $this->fail("Failed to open resource");
        }

        return $resource;
    }

    public function testThatGetBoardsCallsTheBoardsEndPointForMe(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["board"]')));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/members/me/boards')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getBoards();

        $this->assertEquals(['board'], $result);
    }

    /**
     * @param array<string,string> $expected The expected boards
     */
    #[DataProvider('provideDataForBoardByName')]
    public function testThatGetBoardByNameCallsReturnsTheBoardIfTheNameMatches(
        string $boardName,
        string $boards,
        ?array $expected
    ): void {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource($boards)));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/members/me/boards')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getBoardByName($boardName);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array<string,mixed>
     */
    public static function provideDataForBoardByName(): array
    {
        return [
            'Standard name check' => [
                'myBoard',
                '[{"name":"random"},{"name":"myBoard"}]',
                ['name' => 'myBoard']
            ],

            'Casing changes, but board returned' => [
                'myboard',
                '[{"name":"random"},{"name":"MYBOARD"}]',
                ['name' => 'MYBOARD']
            ],

            'No match' => [
                'randomboard-does-not-match',
                '[{"name":"random"},{"name":"MYBOARD"}]',
                null
            ],
        ];
    }

    public function testThatGetCardsCallsTheBoardsEndPointGivenTheBoardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["card"]')));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/boards/1234/cards')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getCards(1234);

        $this->assertEquals(['card'], $result);
    }

    public function testThatGetCardChecklistCallsTheCardChecklistEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["card"]')));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/cards/1234/checklists')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getCardChecklist(1234);

        $this->assertEquals(['card'], $result);
    }

    public function testThatGetCardActionsCallsTheCardActionsEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["card"]')));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/cards/1234/actions')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getCardActions(1234);

        $this->assertEquals(['card'], $result);
    }

    public function testThatGetCardMembersCallsTheCardMembersEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["card"]')));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/cards/1234/members')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getCardMembers(1234);

        $this->assertEquals(['card'], $result);
    }

    public function testThatGetListsCallsTheBoardsEndPointGivenTheBoardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["list"]')));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/boards/1234/lists')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getLists(1234);

        $this->assertEquals(['list'], $result);
    }

    public function testThatGetMeberCallsTheMembersEndPointGivenTheMeberId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(new Stream($this->getResource('["member"]')));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/1/members/1234')
            ->willReturn($response);

        $trello = new Client($http);
        $result = $trello->getMember(1234);

        $this->assertEquals(['member'], $result);
    }

    public function testGetHttpClient(): void
    {
        putenv("TRELLO_CLI_KEY=key");
        putenv("TRELLO_CLI_SECRET=secret");
        $config = Adapter::getConfig();

        $trello = Client::instance($config);
        $this->assertInstanceOf('\GuzzleHttp\Client', $trello->getHttpClient());
        $httpClientConfig = $trello->getHttpClient()->getConfig();
        $this->assertEquals(['key' => 'key', 'token' => 'secret'], $httpClientConfig['query']);

        putenv("TRELLO_CLI_KEY=key2");
        putenv("TRELLO_CLI_SECRET=secret2");

        $config = Adapter::getConfig();
        $trello = Client::instance($config);
        $this->assertInstanceOf('\GuzzleHttp\Client', $trello->getHttpClient());
        $httpClientConfig = $trello->getHttpClient()->getConfig();
        $this->assertEquals(['key' => 'key', 'token' => 'secret'], $httpClientConfig['query']);

        $trello->resetInstance();

        putenv("TRELLO_CLI_KEY=key3");
        putenv("TRELLO_CLI_SECRET=secret3");

        $config = Adapter::getConfig();
        $trello = Client::instance($config);
        $this->assertInstanceOf('\GuzzleHttp\Client', $trello->getHttpClient());
        $httpClientConfig = $trello->getHttpClient()->getConfig();
        $this->assertEquals(['key' => 'key3', 'token' => 'secret3'], $httpClientConfig['query']);
    }
}
