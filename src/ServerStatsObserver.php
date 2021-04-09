<?php

declare(strict_types=1);

namespace Idiosyncratic\Amp;

use Amp\Http\Server\HttpServer;
use Amp\Http\Server\ServerObserver;
use Amp\Loop;
use Amp\Promise;
use Amp\Success;
use Psr\Log\LoggerInterface;

use function is_array;
use function memory_get_peak_usage;
use function memory_get_usage;
use function round;
use function sprintf;
use function sys_getloadavg;

class ServerStatsObserver implements ServerObserver
{
    private LoggerInterface $log;

    private int $interval;

    private string $watcherId;

    public function __construct(LoggerInterface $log, int $interval = 1000)
    {
        $this->log = $log;

        $this->interval = $interval;
    }

    /**
     * @return Promise<mixed>
     */
    public function onStart(HttpServer $server) : Promise
    {
        $this->watcherId = Loop::repeat($msInterval = $this->interval, function ($watcherId): void {
            $load = sys_getloadavg();

            $load = is_array($load) ? $load : [0, 0, 0];

            $this->log->debug(sprintf(
                'Mem: %01.2f MB | Peak Mem: %01.2f MB | CPU: %01.2f/%01.2f/%01.2f %%',
                round(memory_get_usage() / (1024 * 1024), 2),
                round(memory_get_peak_usage() / (1024 * 1024), 2),
                round($load[0], 2),
                round($load[1], 2),
                round($load[2], 2),
            ));
        });

        return new Success();
    }

    /**
     * @return Promise<mixed>
     */
    public function onStop(HttpServer $server) : Promise
    {
        Loop::cancel($this->watcherId);

        return new Success();
    }
}
