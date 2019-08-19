<?php

namespace EasyDingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app) {
            return '';
        };
    }
}