<?php
/**
 * File Config
 */

namespace TrelloCli\Config;

use TrelloCli\Config;

/**
 * Responsible for getting config data from the config file
 */
class File extends Config
{
    /**
     * Get the config data from the config.php file
     */
    public function build($relPath = '/../../../config/config.php')
    {
        $path = __DIR__.$relPath;
        if (file_exists($path)) {
            require $path;

            $this
                ->setKey($config['key'])
                ->setSecret($config['secret'])
                ->setValid(true);
        }
    }
}