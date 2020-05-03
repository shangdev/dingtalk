<?php

namespace Rateltalk\DingTalk\Kernal;

use Overtrue\Http\Client as BaseClient;
use Psr\Http\Message\RequestInterface;

class Client extends BaseClient
{
	/**
	 * @var \Rateltalk\DingTalk\Application
	 */
	protected $app;

	/**
	 * @param $app \Rateltalk\DingTalk\Application $app
	 */
	public function __construct($app)
	{
		$this->app = $app;

		parent::__construct($this->app['config']->get('http', []));
	}

	/**
	 * @return $this
	 */
	public function withAccessTokenMiddleware()
	{
		if ($this->getMiddlewares()['access_token']) {
			return $this;
		}

		$middleware = function (callable $handler) {
			return function (RequestInterface $request, array $options) use ($handler) {
				if ($this->app['access_token']) {
					parse_str($request->getUri()->getQuery(), $query);

					$request = $request->withUri(
						$request->getUri()->withQuery(http_build_query(['access_token' => $this->app['access_token']->getToken()] + $query))
					);
				}

				return $handler($request, $options);
			};
		};

		$this->pushMiddleware($middleware, 'access_token');

		return $this;
	}
}