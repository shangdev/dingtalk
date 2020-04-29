<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['request'] = function ($app) {
            return Request::createFromGlobals();
        };
    }
}