<?php

declare(strict_types=1);

use Amp\Http\Server\Middleware\CompressionMiddleware;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Idiosyncratic\AmpRoute\FastRouteDispatcher;
use Idiosyncratic\AmpRoute\MiddlewareRequestHandler;
use Idiosyncratic\AmpRoute\RouteGroup;
use Idiosyncratic\AmpRoute\Router;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    /*
    return new MiddlewareRequestHandler(
        new Router(
            new FastRouteDispatcher($c),
            $c->get(RouteGroup::class),
            $c->get(DocumentRoot::class)
        ),
        new CompressionMiddleware(),
    );
     */

    return new Router(
        new FastRouteDispatcher($c),
        $c->get(RouteGroup::class),
        $c->get(DocumentRoot::class)
    );
};
