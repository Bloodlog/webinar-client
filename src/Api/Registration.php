<?php

namespace Bloodlog\WebinarClient\Api;

use Bloodlog\WebinarClient\Exception\WebinarException;

/**
 * Registration - регистрация на вебинар.
 */
class Registration extends Api
{

    /**
     * Регистрация участника.
     *
     * https://help.webinar.ru/ru/articles/3180528-быстрый-старт-регистрация-участника
     *
     * @param string $eventSessionId
     * @param array $data
     * @throws WebinarException
     * @return array
     */
    public function registerRequest(string $eventSessionId, array $data = []): array
    {
        return $this->post("/eventsessions/{$eventSessionId}/register", [], $data);
    }

    /**
     * Регистрация участника с трансформацией данных.
     * @see https://help.webinar.ru/ru/articles/3180528-быстрый-старт-регистрация-участника
     *
     * @param string $eventSessionId
     * @param array $data
     * @throws WebinarException
     * @return array
     */
    public function registerUser(string $eventSessionId, array $data = []): array
    {
        return $this->registerRequest($eventSessionId, $this->transformWebinar($data));
    }

    /**
     * Подготавливает данные для отправки данных на api webinar.
     *
     * @param array $data
     * @return array
     */
    public function transformWebinar(array $data): array
    {
        if (!array_key_exists('additionalFields', $data)) {
             return $data;
        }
        $additionalField = config('webinar.api.registration.form-transform.additionalFields', []);
        $additionalFieldTransform = [];

        foreach ($additionalField as $key => $item) {
            if (array_key_exists($item, $data['additionalFields'])) {
                $additionalFieldTransform[$key] = $data['additionalFields'][$item];
            }
        }

        unset($data['additionalFields']);
        $data['additionalFields'] = $additionalFieldTransform;

        return $data;
    }
}
