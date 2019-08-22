<?php

namespace EasyDingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            return $app->getConfig();
        };
    }
}