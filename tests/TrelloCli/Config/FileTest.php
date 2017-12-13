<?php
/**
 * Trello Config File Test File
 */

namespace TrelloCli\Test\Config;

use TrelloCli\Config\File;

/**
 * Trello Config File Test Class
 *
 * Responsible for testing \TrelloCli\Config\File
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        putenv('TRELLO_CLI_KEY');
        putenv('TRELLO_CLI_SECRET');
    }

    /**
     * @covers \TrelloCli\Config\File::build
     */
    public function testWeCanGetConfigFromTheEnvironment()
    {
        putenv('TRELLO_CLI_KEY=my-key-env');
        putenv('TRELLO_CLI_SECRET=my-secret-env');

        $config = new File();
        $config->build('/../../../tests/_files/config.test.php');

        $this->assertEquals('my-key-file', $config->getKey());
        $this->assertEquals('my-secret-file', $config->getSecret());
    }
}
