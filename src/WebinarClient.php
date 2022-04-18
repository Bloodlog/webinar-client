<?php

namespace Bloodlog\WebinarClient;

use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\Api\Registration;

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
    public function getClient()
    {
        return $this->client ?: $this->createClient();
    }

    /**
     * @return HttpClient
     */
    private function createClient()
    {
        return new HttpClient([
            'base_uri' => config('webinar.base_url'),
            'headers' => [
                'x-auth-token' => config('webinar.token'),
            ],
        ]);
    }
}
