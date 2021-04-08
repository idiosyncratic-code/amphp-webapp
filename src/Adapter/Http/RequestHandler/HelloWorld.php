<?php

declare(strict_types=1);

namespace Idiosyncratic\Amp\Adapter\Http\RequestHandler;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Amp\Promise;
use Idiosyncratic\AmpRoute\Router;
use Twig\Environment;

use function Amp\call;

class HelloWorld implements RequestHandler
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handleRequest(Request $request) : Promise
    {
        return call(function () use ($request) {
            $args = $request->getAttribute(Router::class);

            $body = $this->twig->render('hello.html.twig', ['name' => ucfirst($args['name'])]);

            return new Response(code: Status::OK, stringOrStream: $body);
        });
    }
}
