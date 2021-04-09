<?php

declare(strict_types=1);

use Amp\ByteStream;
use Amp\Cluster\Cluster;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    if (Cluster::isWorker()) {
        $channel = sprintf('webapp-worker-%s', Cluster::getId());
        $logHandler = Cluster::createLogHandler();
    } else {
        $channel = 'webapp';
        $logHandler = new StreamHandler(ByteStream\getStdOut());
        $logHandler->setFormatter(new ConsoleFormatter());
    }

    $logger = new Logger($channel);

    $logger->pushHandler($logHandler);

    return $logger;
};
