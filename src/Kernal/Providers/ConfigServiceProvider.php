<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rateltalk\DingTalk\Kernal\Utils\Collection;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            return new Collection($app->getConfig());
        };
    }
}