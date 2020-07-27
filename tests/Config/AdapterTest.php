<?php

namespace TrelloCli\Test\Config;

use TrelloCli\Config\Adapter;

/**
 * Responsible for testing \TrelloCli\Config\Adapter
 */
class AdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @inheritdoc
     */
    public function tearDown(): void
    {
        putenv("TRELLO_CLI_KEY");
        putenv("TRELLO_CLI_SECRET");
    }

    /**
     * @covers \TrelloCli\Config\Adapter::getConfig
     */
    public function testTheDefaultIsEnvironment(): void
    {
        putenv("TRELLO_CLI_KEY=key");
        putenv("TRELLO_CLI_SECRET=secret");
        $config = Adapter::getConfig();

        $this->assertInstanceOf('\TrelloCli\Config', $config);
        $this->assertEquals($config->getKey(), "key");
        $this->assertEquals($config->getSecret(), "secret");
    }

    /**
     * @covers \TrelloCli\Config\Adapter::getConfig
     */
    public function testTheDefaultIsAnEmptyConfigFile(): void
    {
        $config = Adapter::getConfig();

        $this->assertInstanceOf('\TrelloCli\Config', $config);
        $this->assertNull($config->getKey());
        $this->assertNull($config->getSecret());
    }
}
