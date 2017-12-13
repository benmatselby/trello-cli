<?php
/**
 * Trello Config Test File
 */

namespace TrelloCli\Test;

use TrelloCli\Config;

/**
 * Trello Config Test Class
 *
 * Responsible for testing \TrelloCli\Config
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Config::setSecret
     * @covers \TrelloCli\Config::getSecret
     * @covers \TrelloCli\Config::setKey
     * @covers \TrelloCli\Config::getKey
     * @covers \TrelloCli\Config::isValid
     * @covers \TrelloCli\Config::setValid
     */
    public function testSettersAndGetters()
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
