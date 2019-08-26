<?php

namespace EasyDingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use EasyDingTalk\Kernal\Utils\Colletcion;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            return new Colletcion($app->getConfig());
        };
    }
}