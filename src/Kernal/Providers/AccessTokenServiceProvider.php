<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rateltalk\DingTalk\Kernal\AccessToken;

class AccessTokenServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		isset($pimple['access_token']) || $pimple['access_token'] = function ($app) {
			return new AccessToken($app);
		};
	}
}