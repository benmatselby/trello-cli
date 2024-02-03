<?php

namespace TrelloCli\Test\Config;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Config\Environment;

#[CoversClass(Environment::class)]
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
