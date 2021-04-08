<?php

declare(strict_types=1);

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Idiosyncratic\AmpRoute\RouteGroup;
use Idiosyncratic\AmpRoute\Router;
use Idiosyncratic\Amp\Adapter\Http\RequestHandler;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    return new RouteGroup(
        '/',
        static function (RouteGroup $group) : void {
            $group->map(
                'GET',
                '/',
                new CallableRequestHandler(static function () {
                    return new Response(
                        Status::OK,
                        ['content-type' => 'text/plain'],
                        'Hello again, world!'
                    );
                })
            );

            $group->map(
                'GET',
                '/hello/{name}',
                RequestHandler\HelloWorld::class,
            );
        },
    );
};
