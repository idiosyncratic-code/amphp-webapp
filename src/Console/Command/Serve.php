<?php

declare(strict_types=1);

namespace Idiosyncratic\Amp\Console\Command;

use Amp\Http\Server\HttpServer;
use Amp\Http\Server\Options;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\ServerObserver;
use Amp\Loop;
use Amp\Socket;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\LockFactory;

use const SIGINT;

final class Serve extends Command
{
    private Options $serverOptions;

    private RequestHandler $requestHandler;

    private LoggerInterface $log;

    private LockFactory $locks;

    /** @var array<ServerObserver> */
    private array $observers;

    /**
     * @param array<ServerObserver> $observers
     */
    public function __construct(
        Options $serverOptions,
        RequestHandler $requestHandler,
        LoggerInterface $log,
        LockFactory $locks,
        array $observers = []
    ) {
        $this->serverOptions = $serverOptions;

        $this->requestHandler = $requestHandler;

        $this->log = $log;

        $this->locks = $locks;

        $this->observers = $observers;

        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('serve');

        $this->setDescription('Start web server');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) : int {
        Loop::run(function () {
            $lock = $this->locks->createLock('phpid-serve', null, true);

            $context = new Socket\BindContext();

            if ($lock->acquire() === false) {
                throw new RuntimeException('Could not start server, another instance is already running');
            }

            $servers = [
                Socket\Server::listen('0.0.0.0:80', $context),
                Socket\Server::listen('[::]:80', $context),
            ];

            $server = new HttpServer(
                $servers,
                $this->requestHandler,
                $this->log,
                $this->serverOptions
            );

            foreach ($this->observers as $observer) {
                $server->attach($observer);
            }

            $this->log->debug(sprintf('Using %s loop driver', get_class(Loop::get())));

            yield $server->start();

            Loop::onSignal(SIGINT, static function (string $watcherId) use ($server, $lock) {
                Loop::cancel($watcherId);

                $lock->release();

                yield $server->stop();
            });
        });

        return Command::SUCCESS;
    }
}
