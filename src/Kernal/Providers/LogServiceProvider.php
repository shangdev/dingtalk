<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple A container instance.
     */
    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app) {
        	// 创建日志服务
			$log = new Logger('EasyDD');
			$log->pushHandler(new StreamHandler('/tmp/easyDD.log', Logger::DEBUG));

            return $log;
        };
    }
}