<?php

declare(strict_types=1);

use Amp\ByteStream\ResourceOutputStream;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    $logger = new Logger('server');

    $logHandler = new StreamHandler(new ResourceOutputStream(STDOUT));

    $logHandler->setFormatter(new ConsoleFormatter());

    $logger->pushHandler($logHandler);

    return $logger;
};
