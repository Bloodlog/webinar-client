<?php

namespace Bloodlog\WebinarClient;

use GuzzleHttp\Client;
use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\Api\Registration;
use GuzzleHttp\ClientInterface;

class WebinarClient
{

    /**
     * @var HttpClient
     */
    private $client;

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
     * @return HttpClient
     */
    public function getClient(): Client
    {
        return $this->client ?: $this->createClient();
    }

    /**
     * @return HttpClient
     */
    private function createClient(): Client
    {
        return new HttpClient([
            'base_uri' => config('webinar.base_url'),
            'headers' => [
                'x-auth-token' => config('webinar.token'),
            ],
        ]);
    }

    /**
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient): WebinarClient
    {
        $this->client = $httpClient;

        return $this;
    }
}
