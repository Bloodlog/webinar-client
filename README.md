# PHP client for webinar.ru API
# Laravel integration client

![](https://img.shields.io/badge/PHP-^7.4-blue)
![](https://img.shields.io/badge/PHP-^8.0-blue)
![](https://img.shields.io/badge/Laravel-^6.0-red)
![](https://img.shields.io/badge/version-1.0.0-lightgrey)

```bash 
composer require bloodlog/webinar-client
```


Подключение:
1. Опубликовать конфиг:
```bash 
php artisan vendor:publish --provider="Bloodlog\WebinarClient\Providers\WebinarClientProvider"   
```
2. Получить API Token для доступа к webinar.ru и добавить в конфиг:

* config/webinar.php
```php
 'token' => 'Enter your api token', // 
 ```

Получить ключ можно здесь:
* https://events.webinar.ru/business/api - ключ
* https://help.webinar.ru/ru/articles/3147750-интеграция-api-с-чего-начать - документация


Для регистрации пользователя на вебинар:

1 Запросить вебинары:
```php 
use Bloodlog\WebinarClient\WebinarClient;

$client = new WebinarClient();

$webinars = $client->events()->webinarsRequest(); // получаем все вебинары

// Здесь нам нужно найти нужный вебинар и взять у него eventSessions -> id
// Или можно воспользоваться полезным запросом ниже
```
Или метод помощник, который удобнее  
```php 
use Bloodlog\WebinarClient\WebinarClient;

$client = new WebinarClient();

$webinars = $client->events()->webinarsList(); // получаем только eventSessionId и имя вебинара
// Response:[
//     'id' => 123, // $eventSession->id,
//     'name' => 'Новый вебинар', // $eventSession->name,
//]
```
2 Запросить eventSessionID ключ и получить additionalFields(поля для регистрации):
(Если дополнительных полей(additionalFields) нет, то можно пропустить)
```php 
$client = new WebinarClient();
$eventSessionId = 123;
$webinars = $client->events()->eventSessions($eventSessionId);
/* Получаем список полей и сохраняем ключи полей. Ключи нужно использовать при регистрации пользователей.
Response:{
    "id": 123,
    "name": "Новое мероприятие сегодня",
    "additionalFields": [
        {
            "key": "cea75f8cd36a4f8567d5068b7e7e05e8",
            "label": "referrer",
            "type": "text",
            "isRequired": true
        }
    ],
}    
*/
```
3 Отправить запрос на регистрацию (Опять используем $eventSessionId из 1 шага):
```php 
$client = new WebinarClient();
$data = [
            'email' => 'example@example.ru',
            'name' => 'Иван',
            'secondName' => 'Иванов',
            'additionalFields' => [
                '62899cefc8855544723baae88cbfce9c' => 'Иванович',
                '62899cefc8855544723baae88cbfce2c' => 'IBM',
            ],
            'role' => 'GUEST',
            'isAutoEnter' => true,
            'sendEmail' => false,
];
$webinars = $client->registration()->registerRequest($eventSessionId, $data); 
// Response: {"participationId":123,"link":"https:\/\/events.webinar.ru\/Test\/9232275\/46a222712a0466960b1bf3a432c22054","contactId":654}
```
Или метод помощник, который удобнее

Мне не удобно было использовать длинные и не удобные ключи для отправки доп полей(additionalFields), 
поэтому я избавился от них с помощью конфига и метода трансформера:

1 нужно зарегистрировать ключи доп полей в конфиге:
Используем сопоставление ключей, слева ключ с вебинара, справа ваш ключ:
* config/webinar.php
```php
 'api' => [
        'registration' => [
            'form-transform' => [ // webinar.ru использует ключи вместо наименования полей. После получения ключей для регистрации нужно сопоставить поля
                'additionalFields' => [
                    '62899cefc8855544723baae88cbfce9c' => 'last_name', // Сопоставление по которому будет произведена замена
                    '62899cefc8855544723baae66cbfce2c' => 'company', // Сопоставление по которому будет произведена замена
                ],
            ],
        ],
    ],
 ```
2 Далее просто используем свои имена полей:
```php 
$client = new WebinarClient();
$data = [
            'email' => 'example@example.ru',
            'name' => 'Иван',
            'secondName' => 'Иванов',
            'additionalFields' => [
                'last_name' => 'Иванович', // Замена на обратный ключ произойдёт внутри метода.
                'company' => 'IBM', // Замена
            ],
            'role' => 'GUEST',
            'isAutoEnter' => true,
            'sendEmail' => false,
];
$webinars = $client->registration()->registerRequest($eventSessionId, $data); 
// Response: {"participationId":123,"link":"https:\/\/events.webinar.ru\/Test\/9232275\/46a222712a0466960b1bf3a432c22054","contactId":654}

```


За доп. информацией обращаться к офф. документации

* https://help.webinar.ru/ru/articles/3180528-быстрый-старт-регистрация-участника
