<?php

namespace LaravelFeiShu\Controllers;

use FeiShu\OpenPlatform\Application;
use FeiShu\OpenPlatform\Server\Guard;
use LaravelFeiShu\Events\OpenPlatform as Events;
use Symfony\Component\HttpFoundation\Response;

class OpenPlatformController extends Controller
{
    /**
     * Register for open platform.
     *
     * @param Application $application
     *
     * @return Response
     */
    public function __invoke(Application $application)
    {
        $server = $application->server;

        $server->on(Guard::EVENT_AUTHORIZED, function ($payload) {
            event(new Events\Authorized($payload));
        });

        return $server->serve();
    }
}
