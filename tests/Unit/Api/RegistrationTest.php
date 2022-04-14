<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Bloodlog\WebinarClient\Api\Api;
use GuzzleHttp\Client as HttpClient;
use Bloodlog\WebinarClient\Api\Registration;
use Bloodlog\WebinarClient\Exception\WebinarException;

class RegistrationTest extends TestCase
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

        return new Registration($httpClient);
    }

    /**
     * Проверяет регистрацию.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessRegister()
    {
        /** @var Registration $client */
        $client = $this->mockClient('register');
        $response = $client->registerRequest(random_int(1, 100), [
            'email' => 'example@example.com',
            'name' => 'Иван',
            'secondName' => 'Иванов',
            'additionalFields' => [
                '62899cefc8855544723baae88cbfce9c' => 'Иванович',
                '62899cefc8855544723baae88cbfce2c' => 'От коллег',
            ],
            'role' => 'GUEST',
            'isAutoEnter' => true,
            'sendEmail' => false,
        ]);

        $this->assertEquals('https://events.webinar.ru/Test/9232275/46a222712a0466960b1bf3a432c22054', $response['link']);
    }

    /**
     * Проверяет регистрацию.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessRegisterUser()
    {
        /** @var Registration $client */
        $client = $this->mockClient('register');
        $response = $client->registerUser(random_int(1, 100), [
            'email' => 'example@example.com',
            'name' => 'Иван',
            'secondName' => 'Иванов',
            'additionalFields' => [
                '62899cefc8855544723baae88cbfce9c' => 'Иванович',
                '62899cefc8855544723baae88cbfce2c' => 'От коллег',
            ],
            'role' => 'GUEST',
            'isAutoEnter' => true,
            'sendEmail' => false,
        ]);

        $this->assertEquals('https://events.webinar.ru/Test/9232275/46a222712a0466960b1bf3a432c22054', $response['link']);
    }

    /**
     * Проверяет выброс исключения.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFailRegister()
    {
        $this->expectException(WebinarException::class);
        $this->expectExceptionCode(400);
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(400, [], $this->fixture('error_bad_request')),
        ]));
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Registration($httpClient);

        $client->registerRequest(1, ['name' => 'test']);
    }

    public function testTransformFormRegister()
    {
        $expectedDontTransformData = ['secondName' => 'Иванов'];
        $expectedArray = [
            '62899cefc8855544723baae88cbfce9c' => 'last name',
            '62899cefc8855544723baae77cbfce9c' => 'IBM',
            '62899cefc8855544723baae88cbfce7c' => 50,
        ];
        $this->app['config']->set('webinar.api.registration.form-transform.additionalFields', [
            '62899cefc8855544723baae88cbfce9c' => 'lname',
            '62899cefc8855544723baae77cbfce9c' => 'org',
            '62899cefc8855544723baae88cbfce7c' => 'age',
            'cea75f8cd36a4f8567d5068b7e7e05e7' => 'referrer',
        ]);
        /** @var Registration $client */
        $client = $this->mockClient('register');

        $transform = $client->transformWebinar([
            'secondName' => 'Иванов',
            'additionalFields' => [
                'lname' => 'last name', 'org' => 'IBM', 'age' => 50,
            ],
        ]);

        $this->assertEquals($expectedDontTransformData['secondName'], $transform['secondName']);
        $this->assertEquals($expectedArray['62899cefc8855544723baae88cbfce9c'], $transform['additionalFields']['62899cefc8855544723baae88cbfce9c']);
        $this->assertEquals($expectedArray['62899cefc8855544723baae77cbfce9c'], $transform['additionalFields']['62899cefc8855544723baae77cbfce9c']);
        $this->assertEquals($expectedArray['62899cefc8855544723baae88cbfce7c'], $transform['additionalFields']['62899cefc8855544723baae88cbfce7c']);
        $this->assertFalse(array_key_exists('cea75f8cd36a4f8567d5068b7e7e05e7', $expectedArray));
    }
}
