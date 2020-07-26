<?php

/**
 * Config Adapter
 */

namespace TrelloCli\Config;

use TrelloCli\Config;

/**
 * Responsible for deciding which config data to use
 */
class Adapter
{
    /**
     * @return Config
     */
    public static function getConfig()
    {
        $environment = new Environment();
        $environment->build();

        if ($environment->isValid()) {
            return $environment;
        }

        return new Config();
    }
}
