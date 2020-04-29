<?php

namespace Rateltalk\DingTalk\Kernal\Providers;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpClientServiceProvider implements ServiceProviderInterface
{
	/**
	 * @param Container $pimple A container instance.
	 */
	public function register(Container $pimple)
	{
		$pimple['http_client'] = function ($app) {
			return new Client($app['config']->get('http', []));
		};
	}
}