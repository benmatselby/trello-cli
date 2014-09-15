<?php
/**
 * Trello Client Test File
 */

namespace TrelloCli\Test;

use TrelloCli\Client;
use GuzzleHttp\Client as HttpClient;

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
     * @covers \TrelloCli\Client::getHttpClient
     */
    public function testThatTheConstructorSetsADefaultHttpClientIfNonePassedIn()
    {
        $container = array(
            'trello.cli.config' => [
                'trello' => [
                    'key'    => 'aKey',
                    'secret' => 'aSecret',
                ]
            ]
        );

        $trello = new Client($container);

        $actualClient = $trello->getHttpClient();

        $this->assertEquals('https://api.trello.com/', $actualClient->getBaseUrl());
    }

    /**
     * @covers \TrelloCli\Client::__construct
     * @covers \TrelloCli\Client::getBoards
     */
    public function testThatGetBoardsCallsTheBoardsEndPointForMe()
    {
        $container = [];

        $response = $this->getMock(
            '\stdClass',
            array('json')
        );
        $response
            ->expects($this->once())
            ->method('json')
            ->will($this->returnValue(['board']));

        $http = $this->getMock(
            '\stdClass',
            array('get')
        );

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
     * @covers \TrelloCli\Client::getCards
     */
    public function testThatGetCardsCallsTheBoardsEndPointGivenTheBoardId()
    {
        $container = [];

        $response = $this->getMock(
            '\stdClass',
            array('json')
        );
        $response
            ->expects($this->once())
            ->method('json')
            ->will($this->returnValue(['card']));

        $http = $this->getMock(
            '\stdClass',
            array('get')
        );

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
     * @covers \TrelloCli\Client::getLists
     */
    public function testThatGetListsCallsTheBoardsEndPointGivenTheBoardId()
    {
        $container = [];

        $response = $this->getMock(
            '\stdClass',
            array('json')
        );
        $response
            ->expects($this->once())
            ->method('json')
            ->will($this->returnValue(['list']));

        $http = $this->getMock(
            '\stdClass',
            array('get')
        );

        $http
            ->expects($this->once())
            ->method('get')
            ->with('/1/boards/1234/lists')
            ->will($this->returnValue($response));

        $trello = new Client($container, $http);
        $result = $trello->getLists('1234');

        $this->assertEquals(['list'], $result);
    }
}
