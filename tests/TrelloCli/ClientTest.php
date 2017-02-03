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
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getHttpClient
     */
    public function testThatTheConstructorSetsHttpClientIfObjectPassedIn()
    {
        $container = [];

        $http = new \stdClass();
        $http->name = 'Something';

        $trello = new Client($container, $http);

        $actualClient = $trello->getHttpClient();

        $this->assertEquals($http, $actualClient);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getBoards
     */
    public function testThatGetBoardsCallsTheBoardsEndPointForMe()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["board"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/me/boards')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getBoards();

        $this->assertEquals(['board'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getBoardByName
     * @dataProvider provideDataForBoardByName
     */
    public function testThatGetBoardByNameCallsReturnsTheBoardIfTheNameMatches($boardName, $boards, $expected)
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($boards));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/me/boards')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getBoardByName($boardName);

        $this->assertEquals($expected, $result);
    }

    public function provideDataForBoardByName()
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
    public function testThatGetCardsCallsTheBoardsEndPointGivenTheBoardId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/boards/1234/cards')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getCards('1234');

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardChecklist
     */
    public function testThatGetCardChecklistCallsTheCardChecklistEndPointWithCardId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/checklists')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getCardChecklist('1234');

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardActions
     */
    public function testThatGetCardActionsCallsTheCardActionsEndPointWithCardId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/actions')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getCardActions('1234');

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getCardMembers
     */
    public function testThatGetCardMembersCallsTheCardMembersEndPointWithCardId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["card"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/cards/1234/members')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getCardMembers('1234');

        $this->assertEquals(['card'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getLists
     */
    public function testThatGetListsCallsTheBoardsEndPointGivenTheBoardId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["list"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/boards/1234/lists')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getLists('1234');

        $this->assertEquals(['list'], $result);
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getMember
     */
    public function testThatGetMeberCallsTheMembersEndPointGivenTheMeberId()
    {
        $container = [];

        $response = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['getBody'])
            ->getMock();
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('["member"]'));

        $http = $this
            ->getMockBuilder('\stdClass')
            ->setMethods(['get'])
            ->getMock();

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/members/1234')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getMember('1234');

        $this->assertEquals(['member'], $result);
    }
}
