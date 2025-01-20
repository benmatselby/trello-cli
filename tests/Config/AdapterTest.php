<?php

namespace TrelloCli\Test\Config;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Config\Adapter;

#[CoversClass(Adapter::class)]
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

    public function testTheDefaultIsEnvironment(): void
    {
        putenv("TRELLO_CLI_KEY=key");
        putenv("TRELLO_CLI_SECRET=secret");
        $config = Adapter::getConfig();

        $this->assertInstanceOf('\TrelloCli\Config', $config);
        $this->assertEquals($config->getKey(), "key");
        $this->assertEquals($config->getSecret(), "secret");
    }

    public function testTheDefaultIsAnEmptyConfigFile(): void
    {
        $config = Adapter::getConfig();

        $this->assertInstanceOf('\TrelloCli\Config', $config);
        $this->assertEquals($config->getKey(), "");
        $this->assertEquals($config->getSecret(), "");
    }
}
