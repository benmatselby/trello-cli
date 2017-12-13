<?php
/**
 * Trello Config Adapter Test File
 */

namespace TrelloCli\Test\Config;

use TrelloCli\Config\Adapter;

/**
 * Trello Config Adapter Test Class
 *
 * Responsible for testing \TrelloCli\Config\Adapter
 */
class AdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \TrelloCli\Config\Adapter::getConfig
     */
    public function testTheDefaultIsAnEmptyConfigFile()
    {
        $config = Adapter::getConfig();

        $this->assertInstanceOf('\TrelloCli\Config', $config);
    }
}
