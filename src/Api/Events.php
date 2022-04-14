<?php

namespace Bloodlog\WebinarClient\Api;

use Bloodlog\WebinarClient\Exception\WebinarException;

/**
 * Events - Мероприятия
 */
class Events extends Api
{

    /**
     * Возвращает все вебинары.
     *
     * @see https://help.webinar.ru/ru/articles/3148981-получить-информацию-о-мероприятиях
     *
     * @param int $page - номер страницы выборки.
     * @param int $perPage - количество элементов на одной странице выборки. По умолчанию: 10.
     * @param array $status -  статус вебинаров. Передается в виде массива статусов
     * @param string $from - Если параметр from не указан, выборка будет осуществляться от текущей даты и времени.
     * @return array
     * @throws WebinarException
     */
    public function webinarsRequest(int $page = 1, int $perPage = 10, array $status = [], string $from = ''): array
    {
        return $this->get(
            '/organization/events/schedule',
            compact('page', 'perPage', 'status', 'from')
        );
    }

    /**
     * Получить данные о вебинаре.
     * Возвращает ключи additionalFields полей.
     * @see https://help.webinar.ru/ru/articles/3148997-получить-данные-о-вебинаре
     *
     * @param int $eventSessionId - например 10842945
     * @return array
     * @throws WebinarException
     */
    public function eventSessions(int $eventSessionId): array
    {
        return $this->get('/organization/{$eventSessionId}');
    }

    /**
     * Возвращает вебинары с сайта webinar.ru.
     * массив содержит name и id(event_session) - для последующей отправки формы регистрации участников.
     *
     * @param int $page
     * @param int $perPage
     * @param array $status
     * @param string $from
     * @return array
     * @throws WebinarException
     */
    public function webinarsList(int $page = 1, int $perPage = 10, array $status = [], string $from = ''): array
    {
        $allowWebinars = [];
        foreach ($this->webinarsRequest() as $webinar) {
            foreach ($webinar->eventSessions as $eventSession) {
                if (!isset($eventSession->id) || !isset($eventSession->name)) {
                    continue;
                }
                $allowWebinars[] = [
                    'id' => $eventSession->id,
                    'name' => $eventSession->name,
                ];
            }
        }

        return $allowWebinars;
    }

}