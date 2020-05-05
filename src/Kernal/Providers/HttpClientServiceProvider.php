<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rateltalk\DingTalk\Kernal\Client;

class HttpClientServiceProvider implements ServiceProviderInterface
{
	/**
	 * @param Container $pimple A container instance.
	 */
	public function register(Container $pimple)
	{
		isset($pimple['http_client']) || $pimple['http_client'] = function ($app) {
			return new Client($app);
		};
	}
}