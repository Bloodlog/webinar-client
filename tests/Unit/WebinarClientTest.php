<?php

namespace Tests\Unit\Support\Webinar;

use Tests\TestCase;
use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\WebinarClient;
use Bloodlog\WebinarClient\Api\Registration;

class WebinarClientTest extends TestCase
{

    /**
     * @var WebinarClient
     */
    private $client;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        /** @var WebinarClient client */
        $this->client = app()->make(WebinarClient::class);
    }

    public function testWebinarClient()
    {
        $this->assertInstanceOf(HttpClient::class, $this->client->getClient());
    }

    public function testEvents()
    {
        $this->assertInstanceOf(Events::class, $this->client->events());
    }

    public function testRegistration()
    {
        $this->assertInstanceOf(Registration::class, $this->client->registration());
    }
}
