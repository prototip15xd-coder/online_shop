<?php

namespace Service;

use Throwable;

class LoggerService
{
    public function error(Throwable $exception): void
    {
        $message = sprintf(
            "[%s] Ошибка: %s в файле %s на строке %s\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
//        $logFile = __DIR__ . '/../../Storage/Log/errors.txt';
        $logFile = __DIR__ . '/../Storage/Log/errors.txt';
        file_put_contents($logFile, $message, FILE_APPEND);
//        file_put_contents('/var/www/html/Storage/Log/errors.txt', $message, FILE_APPEND);
    }
    public function getErrors(): string
    {
        if (!file_exists('errors.txt')) {
            return 'Лог ошибок пуст.';
        }

        return file_get_contents('errors.txt');
    }

}