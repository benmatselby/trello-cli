<?php

namespace TrelloCli;

/**
 * Responsible for housing config data
 */
class Config
{
    /**
     * The Trello Key
     *
     * @var string
     */
    protected $key;

    /**
     * The Trello secret
     *
     * @var string
     */
    protected $secret;

    /**
     * If the config is valid
     *
     * @var boolean
     */
    protected $valid = false;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     *
     * @return Config
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     *
     * @return Config
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Is the configuration valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @param boolean $valid Is the configuration valid
     *
     * @return Config
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
    }
}
