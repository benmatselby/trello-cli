<?php

namespace TrelloCli\Test\Config;

use TrelloCli\Config\Environment;

/**
 * Trello Config Environment Test Class
 *
 * Responsible for testing \TrelloCli\Config\Environment
 */
class EnvironmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @inheritdoc
     */
    public function tearDown(): void
    {
        putenv('TRELLO_CLI_KEY');
        putenv('TRELLO_CLI_SECRET');
    }

    /**
     * @covers \TrelloCli\Config\Environment::build
     */
    public function testWeCanGetConfigFromTheEnvironment(): void
    {
        putenv('TRELLO_CLI_KEY=my-key-env');
        putenv('TRELLO_CLI_SECRET=my-secret-env');

        $config = new Environment();
        $config->build();

        $this->assertEquals('my-key-env', $config->getKey());
        $this->assertEquals('my-secret-env', $config->getSecret());
    }
}
