<?php

declare(strict_types=1);

use Amp\Http\Server\Options as ServerOptions;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Socket\Server as SocketServer;
use Idiosyncratic\Amp\ServerStatsObserver;
use Twig\Environment as TwigEnvironment;

return [
    ServerStatsObserver::class => ['interval' => 5000],
    TwigEnvironment::class => ['cache' => 'var/cache'],
    SocketServer::class => [
        [
            'address' => '0.0.0.0',
            'port' => '80',
        ],
        [
            'address' => '[::]',
            'port' => '80',
        ],
    ],
    ServerOptions::class => [
        'debug' => false,
        'connectionLimit' => 10000,
        'connectionsPerIpLimit' => 1000,
        'http1Timeout' => 15,
        'http2Timeout' => 60,
        'tlsSetupTimeout' => 5,
        'concurrentStreamLimit' => 256,
        'allowedMethods' => ['GET', 'POST', 'PUT', 'PATCH', 'HEAD', 'OPTIONS', 'DELETE'],
        'bodySizeLimit' => 131072,
        'headerSizeLimit' => 32768,
        'chunkSize' => 8192,
        'streamThreshold' => 8192,
        'compression' => true,
        'allowHttp2Upgrade' => false,
        'pushEnabled' => true,
        'requestLogContext' => false,
    ],
    DocumentRoot::class => [],
];
