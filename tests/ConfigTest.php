<?php

namespace TrelloCli\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use TrelloCli\Config;

#[CoversClass(Config::class)]
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testSettersAndGetters(): void
    {
        $config = new Config();
        $config
            ->setKey('my-key')
            ->setSecret('is-safe')
            ->setValid(true);

        $this->assertTrue($config->isValid());
        $this->assertEquals('my-key', $config->getKey());
        $this->assertEquals('is-safe', $config->getSecret());
    }
}
