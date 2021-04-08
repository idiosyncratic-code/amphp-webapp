<?php

declare(strict_types=1);

use Amp\Http\Server\StaticContent\DocumentRoot;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    $defaultConfig = [
        'root' => sprintf('%s/public', dirname(__DIR__)),
    ];

    $config = array_merge($defaultConfig, $c->get('config')[DocumentRoot::class]);

    return new DocumentRoot($config['root']);
};
