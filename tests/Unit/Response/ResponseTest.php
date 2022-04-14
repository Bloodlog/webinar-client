<?php

namespace Tests\Unit\Response;

use Tests\TestCase;
use Bloodlog\WebinarClient\Response\Response;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use Bloodlog\WebinarClient\Exception\WebinarTransformResponseException;

class ResponseTest extends TestCase
{

    /**
     * Проверяет успешное конвертирование ответа json в массив.
     *
     * @return void
     * @throws \Exception
     */
    public function testSuccessTransformResponse()
    {
        $responseTransform = new Response();
        $data = ['test' => 123];
        $response = new GuzzleHttpResponse(200, ['Content-Type' => 'application/json'], json_encode($data));

        $this->assertEquals($data, $responseTransform->transform($response));
    }

    /**
     * Проверяет работу на пустом ответе.
     *
     * @return void
     * @throws \Exception
     */
    public function testSuccessEmptyTransformResponse()
    {
        $responseTransform = new Response();
        $data = [];
        $response = new GuzzleHttpResponse(200, ['Content-Type' => 'application/json'], json_encode($data));

        $this->assertEquals($data, $responseTransform->transform($response));
    }

    /**
     * Проверяет успешный выброс исключения.
     *
     * @return void
     * @throws \Exception
     */
    public function testExceptionEmptyTransformResponse()
    {
        $responseTransform = new Response();
        $response = new GuzzleHttpResponse(200, ['Content-Type' => 'application/json'], '');
        $this->expectException(WebinarTransformResponseException::class);

        $responseTransform->transform($response);
    }
}