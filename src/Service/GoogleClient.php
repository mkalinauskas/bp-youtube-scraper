<?php

namespace App\Service;

class GoogleClient
{
    /**
     * @var \Google_Client client
     */
    protected $client;

    /**
     * @param string $google_api_key
     */
    public function __construct($google_api_key)
    {
        $client = new \Google_Client();
        $client->setDeveloperKey($google_api_key);

        $this->client = $client;
    }

    /**
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }
}