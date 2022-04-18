<?php

namespace Bloodlog\WebinarClient\Api;

use Bloodlog\WebinarClient\Exception\ClientResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Bloodlog\WebinarClient\Response\Response;
use Bloodlog\WebinarClient\Exception\WebinarException;

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
            $response = $this->client->request('GET', $this->version
                . $uri, ['query' => $query]);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $errors = json_decode((string)$e->getResponse()->getBody(), true, 512, JSON_THROW_ON_ERROR);
                if (array_key_exists('error', $errors)) {
                    throw new ClientResponseException($errors['error']['message'], $e->getResponse()->getStatusCode(), $e);
                }
            }
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
            $response = $this->client->request('POST', $this->version
                . $uri, ['query' => $query, 'form_params' => $form]);
        } catch (ClientException $e) {
            throw new WebinarException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->response->transform($response);
    }
}
