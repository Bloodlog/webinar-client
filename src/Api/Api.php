<?php

namespace Bloodlog\WebinarClient\Api;

use Bloodlog\WebinarClient\Exception\WebinarException;
use Exception;
use GuzzleHttp\Client;
use Bloodlog\WebinarClient\Response\Response;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;

class Api
{
    /** @var Client */
    protected $client;

    /**
     * @var Response
     */
    public $response;

    /**
     * Api constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->response = new Response();
        $this->version = config('webinar.version_api');
    }

    /**
     * @param string $uri
     * @param array $query
     * @return array
     * @throws WebinarException
     */
    public function get(string $uri, array $query = []): array
    {
        try {
            $response = $this->client->request('GET', '/api/' . $this->version
                . $uri, ['query' => $query]);
        } catch (ClientException $e) {
            throw new WebinarException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->response->transform($response);
    }

    /**
     * @param string $uri
     * @param array $query
     * @param array $form
     * @return array
     * @throws WebinarException
     */
    public function post(string $uri, array $query = [], array $form = []): array
    {
        try {
            $response = $this->client->request('POST', '/api/' . $this->version
                . $uri, ['query' => $query, 'form_params' => $form]);
        } catch (ClientException $e) {
            throw new WebinarException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->response->transform($response);
    }
}