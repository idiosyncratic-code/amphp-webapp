<?php

declare(strict_types=1);

use Idiosyncratic\Amp\ServerStatsObserver;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return static function (ContainerInterface $c) {
    $config = $c->get('config')[ServerStatsObserver::class];

    return [
        new ServerStatsObserver($c->get(LoggerInterface::class), $config['interval']),
    ];
};
