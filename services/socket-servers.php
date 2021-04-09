<?php

declare(strict_types=1);

use Amp\Socket\BindContext;
use Amp\Socket\Server;
use Psr\Container\ContainerInterface;
use Amp\Cluster\Cluster;

return static function (ContainerInterface $c) {
    $config = $c->get('config')[Server::class];

    $context = new BindContext();

    $sockets = array_map(function ($socket) use ($context) {
        return Cluster::listen(sprintf('%s:%s', $socket['address'], $socket['port']), $context);
    }, $config);

    return $sockets;
};
