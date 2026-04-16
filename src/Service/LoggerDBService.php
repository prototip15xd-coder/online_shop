<?php

declare(strict_types=1);

namespace Service;

use Model\Logger;
use Throwable;

class LoggerDBService
{
    private Logger $loggerModel;

    public function __construct()
    {
        $this->loggerModel = new Logger();
    }

    public function error(Throwable $exception): void
    {
        $this->loggerModel->error($exception->getMessage(), $exception->getFile(),$exception->getLine());
    }
}
