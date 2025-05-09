<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\FilterHandler;

class FilterInfoLevel
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $logger->setHandlers([
                new FilterHandler($handler, Logger::INFO, Logger::INFO),
            ]);
        }
    }
}
