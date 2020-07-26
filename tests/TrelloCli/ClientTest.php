<?php

/**
 * Trello Client Test File
 */

namespace TrelloCli\Test;

use TrelloCli\Client;

/**
 * Trello Client Test Class
 *
 * Responsible for testing \TrelloCli\Client
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @inheritdoc
     */
    public function tearDown(): void
    {
        Client::resetInstance();
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getBoards
     */
    public function testThatGetBoardsCallsTheBoardsEndPointForMe(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');

        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["board"]'));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/me/boards')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getBoards();

        $this->assertEquals(['board'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getBoardByName
     * @dataProvider provideDataForBoardByName
     *
     * @param ?array<string,string> $expected The expected boards
     */
    public function testThatGetBoardByNameCallsReturnsTheBoardIfTheNameMatches(
        string $boardName,
        string $boards,
        ?array $expected
    ): void {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($boards));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/me/boards')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getBoardByName($boardName);

        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for testThatGetBoardByNameCallsReturnsTheBoardIfTheNameMatches
     *
     * @return array<string,array>
     */
    public function provideDataForBoardByName(): array
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

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCards
     */
    public function testThatGetCardsCallsTheBoardsEndPointGivenTheBoardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this
            ->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/boards/1234/cards')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getCards(1234);

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardChecklist
     */
    public function testThatGetCardChecklistCallsTheCardChecklistEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/checklists')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getCardChecklist(1234);

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardActions
     */
    public function testThatGetCardActionsCallsTheCardActionsEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/actions')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getCardActions(1234);

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardMembers
     */
    public function testThatGetCardMembersCallsTheCardMembersEndPointWithCardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/members')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getCardMembers(1234);

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getLists
     */
    public function testThatGetListsCallsTheBoardsEndPointGivenTheBoardId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["list"]'));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/boards/1234/lists')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getLists(1234);

        $this->assertEquals(['list'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getMember
     */
    public function testThatGetMeberCallsTheMembersEndPointGivenTheMeberId(): void
    {
        $response = $this->createMock('\Psr\Http\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["member"]'));

        $http = $this->createMock('\GuzzleHttp\Client');

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/1234')
            ->will($this->returnValue($response));

        $trello = new Client($http);
        $result = $trello->getMember(1234);

        $this->assertEquals(['member'], $result);
    }
}
