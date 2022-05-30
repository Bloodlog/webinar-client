<?php

namespace Bloodlog\WebinarClient;

use GuzzleHttp\Client;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\Api\Registration;

class WebinarClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->setHttpClient($httpClient);
    }

    /**
     * @return Events
     */
    public function events(): Events
    {
        return new Events($this->getClient());
    }

    /**
     * @return Registration
     */
    public function registration(): Registration
    {
        return new Registration($this->getClient());
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $httpClient
     * @return $this
     */
    public function setHttpClient(Client $httpClient): WebinarClient
    {
        $this->client = $httpClient;

        return $this;
    }
}
