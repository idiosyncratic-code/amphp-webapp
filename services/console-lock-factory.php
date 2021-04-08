<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

return static function (ContainerInterface $c) {
    $store = new FlockStore();

    return new LockFactory($store);
};
