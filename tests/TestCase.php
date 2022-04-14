<?php

namespace Tests;

use Bloodlog\WebinarClient\Providers\WebinarClientProvider;

/**
 * Class TestCase.
 *
 * @package Tests
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Возвращает фикстуру по имени в формате массива.
     *
     * @param $name
     * @return string
     */
    public function fixture($name): string
    {
        return file_get_contents(__DIR__ . '/Fixtures/Webinars/' . $name . '.json');
    }

    /**
     * Регистрация провайдеров.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            WebinarClientProvider::class,
        ];
    }
}
