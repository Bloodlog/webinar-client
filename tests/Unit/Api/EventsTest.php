<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Bloodlog\WebinarClient\Api\Api;
use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Events;
use Bloodlog\WebinarClient\Exception\ClientResponseException;

class EventsTest extends TestCase
{

    /**
     * Замокровать ответ клиента.
     *
     * @param string $responseFile имя файла с ожидаемым json ответом.
     * @return Api
     */
    private function mockClient(string $responseFile): Api
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], $this->fixture($responseFile)),
        ]));

        $httpClient = new HttpClient(['handler' => $handlerStack]);

        return new Events($httpClient);
    }

    /**
     * Проверяет вывод.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessWebinars()
    {
        $client = $this->mockClient('webinars');

        $response = $client->webinarsRequest();

        $this->assertEquals('8762033', $response[0]->eventSessions[0]->id);
        $this->assertEquals('Анализ ', $response[0]->eventSessions[0]->name);
    }

    /**
     * Проверяет работу, если api вернул пустой ответ.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testEmptyWebinars()
    {
        $client = $this->mockClient('webinars_empty');

        $response = $client->webinarsRequest();

        $this->assertEmpty($response);
    }

    /**
     * Проверяет работу маршрута eventSession
     *
     * @return void
     * @throws \Exception
     */
    public function testSuccessEventSession()
    {
        /** @var Events $client */
        $client = $this->mockClient('event_session');
        $eventSessionId = 10842945;

        $response = $client->eventSessions($eventSessionId);

        $this->assertEquals($eventSessionId, $response['id']);
        $this->assertEquals('Новое мероприятие сегодня', $response['name']);
    }

    /**
     * Проверяет выброс исключения.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFailEvents()
    {
        $this->expectException(ClientResponseException::class);
        $this->expectExceptionCode(404);
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(404, [], $this->fixture('404')),
        ]));
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Events($httpClient);

        $client->eventSessions(1);
    }
}
