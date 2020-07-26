<?php

/**
 * Environment Config
 */

namespace TrelloCli\Config;

use TrelloCli\Config;

/**
 * Responsible for getting config data from env
 */
class Environment extends Config
{
    /**
     * Get the config data from the local environment
     */
    public function build(): void
    {
        $key = getenv('TRELLO_CLI_KEY', true);
        $secret = getenv('TRELLO_CLI_SECRET', true);

        if ($key !== false && $secret !== false) {
            $this
                ->setKey($key)
                ->setSecret($secret)
                ->setValid(true);
        }
    }
}
