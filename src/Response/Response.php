<?php

namespace Bloodlog\WebinarClient\Response;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Bloodlog\WebinarClient\Exception\WebinarTransformResponseException;

class Response
{
    /**
     * Возвращает преобразованную строку в массив.
     *
     * @param ResponseInterface $response
     * @return array
     * @throws Exception
     */
    public function transform(ResponseInterface $response): array
    {
        $body = (string)$response->getBody();
        try {
            $content = (array)json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new WebinarTransformResponseException($e->getMessage(), $e->getCode(), $e);
        }

        return $content;
    }


}