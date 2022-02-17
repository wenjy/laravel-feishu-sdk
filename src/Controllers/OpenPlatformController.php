<?php

namespace LaravelFeiShu\Controllers;

use FeiShu\OpenPlatform\Application;
use FeiShu\OpenPlatform\Server\Guard;
use Overtrue\LaravelWeChat\Events\OpenPlatform as Events;

class OpenPlatformController extends Controller
{
    /**
     * Register for open platform.
     *
     * @param \EasyWeChat\OpenPlatform\Application $application
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Application $application)
    {
        $server = $application->server;

        $server->on(Guard::EVENT_AUTHORIZED, function ($payload) {
            event(new Events\Authorized($payload));
        });
        $server->on(Guard::EVENT_UNAUTHORIZED, function ($payload) {
            event(new Events\Unauthorized($payload));
        });
        $server->on(Guard::EVENT_UPDATE_AUTHORIZED, function ($payload) {
            event(new Events\UpdateAuthorized($payload));
        });
        $server->on(Guard::EVENT_COMPONENT_VERIFY_TICKET, function ($payload) {
            event(new Events\VerifyTicketRefreshed($payload));
        });

        return $server->serve();
    }
}
