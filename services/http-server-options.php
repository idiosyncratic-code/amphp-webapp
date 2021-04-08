<?php

declare(strict_types=1);

use Amp\Http\Server\Options;
use Psr\Container\ContainerInterface;

return static function (ContainerInterface $c) {
    $defaultConfig = [
        'debug' => false,
        'connectionLimit' => 10000,
        'connectionsPerIpLimit' => 30,
        'http1Timeout' => 15,
        'http2Timeout' => 60,
        'tlsSetupTimeout' => 5,
        'concurrentStreamLimit' => 256,
        'allowedMethods' => ["GET", "POST", "PUT", "PATCH", "HEAD", "OPTIONS", "DELETE"],
        'bodySizeLimit' => 131072,
        'headerSizeLimit' => 32768,
        'chunkSize' => 8192,
        'streamThreshold' => 8192,
        'compression' => true,
        'allowHttp2Upgrade' => false,
        'pushEnabled' => true,
        'requestLogContext' => false,
    ];

    $config = array_merge($defaultConfig, $c->get('config')[Options::class]);

    $options = new Options();

    $options = $options->withConnectionLimit($config['connectionLimit'])
        ->withConnectionsPerIpLimit($config['connectionsPerIpLimit'])
        ->withHttp1Timeout($config['http1Timeout'])
        ->withHttp2Timeout($config['http2Timeout'])
        ->withTlsSetupTimeout($config['tlsSetupTimeout'])
        ->withBodySizeLimit($config['bodySizeLimit'])
        ->withHeaderSizeLimit($config['headerSizeLimit'])
        ->withConcurrentStreamLimit($config['concurrentStreamLimit'])
        ->withChunkSize($config['chunkSize'])
        ->withStreamThreshold($config['streamThreshold'])
        ->withAllowedMethods($config['allowedMethods']);

    $boolMethods = [];
    $boolMethods[] = $config['debug'] === true ? 'withDebugMode' : 'withoutDebugMode';
    $boolMethods[] = $config['compression'] === true ? 'withCompression' : 'withoutCompression';
    $boolMethods[] = $config['allowHttp2Upgrade'] === true ? 'withHttp2Upgrade' : 'withoutHttp2Upgrade';
    $boolMethods[] = $config['pushEnabled'] === true ? 'withPush' : 'withoutPush';
    $boolMethods[] = $config['requestLogContext'] === true ? 'withRequestLogContext' : 'withoutRequestLogContext';

    $options = array_reduce($boolMethods, function($options, $method) {
        return call_user_func([$options, $method]);
    }, $options);

    return $options;
};
