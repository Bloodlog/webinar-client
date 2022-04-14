<?php

namespace Tests\Unit\Support\Webinar;

use Tests\TestCase;
use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\WebinarClient;
use Bloodlog\WebinarClient\Api\Registration;

class WebinarClientTest extends TestCase
{

    public function testWebinarClient()
    {
        $client = new WebinarClient();

        $this->assertInstanceOf(HttpClient::class, $client->getClient());
    }

    public function testEvents()
    {
        $client = new WebinarClient();

        $this->assertInstanceOf(Events::class, $client->events());
    }

    public function testRegistration()
    {
        $client = new WebinarClient();

        $this->assertInstanceOf(Registration::class, $client->registration());
    }
}