<?php

/**
 * 机器人模块
 */

namespace Rateltalk\DingTalk\Robot;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		$pimple['robot'] = function ($app) {
			return new Client($app);
		};
	}
}