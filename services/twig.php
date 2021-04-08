<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return static function (ContainerInterface $c) {
    $config = $c->get('config')[Environment::class];

    $loader = new FilesystemLoader('twig');

    $twig = new Environment($loader, [
        'cache' => $config['cache'],
    ]);

    return $twig;
};
