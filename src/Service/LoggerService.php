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
        $logFile = __DIR__ . '/../Storage/Log/errors.txt';
        file_put_contents($logFile, $message, FILE_APPEND);
    }
}