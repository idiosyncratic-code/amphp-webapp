<?php

declare(strict_types=1);

use Amp\Http\Server\Options;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\ServerObserver;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Socket\Server as SocketServer;
use DI\ContainerBuilder;
use Idiosyncratic\AmpRoute\RouteGroup;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Twig\Environment as TwigEnvironment;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    'config' => require_once('config/config.php'),
    DocumentRoot::class => require_once('services/document-root.php'),
    RouteGroup::class => require_once('services/routes.php'),
    RequestHandler::class => require_once('services/request-handler.php'),
    LoggerInterface::class => require_once('services/log.php'),
    Options::class => require_once('services/http-server-options.php'),
    LockFactory::class => require_once('services/console-lock-factory.php'),
    ServerObserver::class => require_once('services/observers.php'),
    TwigEnvironment::class => require_once('services/twig.php'),
    SocketServer::class => require_once('services/socket-servers.php'),
]);

return $builder->build();
