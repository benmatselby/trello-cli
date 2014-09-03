<?php
/**
 * Trello Client
 */

namespace TrelloCli;

use GuzzleHttp\Client as HttpClient;

/**
 * Minimal wrapper around Guzzle http client
 *
 * Responsible for bootstrapping the http client
 */
class Client
{
    /**
     * Cilex application
     */
    protected $container;

    /**
     * GuzzleHttp client
     */
    protected $httpClient;

    /**
     * Constructor
     *
     * @param CilexApplication $container Application container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Wrapper to the guzzlehttp client
     *
     * @return GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if ($this->httpClient === null) {

            $client = new HttpClient([
                'base_url' => 'https://api.trello.com/',
                'defaults' => [
                    'query' => [
                        'key' => $this->container['trello.cli.config']['trello']['key'],
                        'token' => $this->container['trello.cli.config']['trello']['secret']
                    ]
                ]
            ]);

            $this->httpClient = $client;
        }

        return $this->httpClient;
    }
}
