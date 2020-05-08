<?php

namespace Rateltalk\DingTalk\Kernal;

use GuzzleHttp\Middleware;
use Overtrue\Http\Client as BaseClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
	 * 添加 access_token 到参数中
	 * @return $this
	 */
	public function withAccessTokenMiddleware()
	{
		if (isset($this->getMiddlewares()['access_token'])) {
			return $this;
		}

		$middleware = function (callable $handler) {
			return function (RequestInterface $request, array $options) use ($handler) {
				if ($this->app['auth']) {
					parse_str($request->getUri()->getQuery(), $query);

					$request = $request->withUri(
						$request->getUri()->withQuery(http_build_query(['access_token' => $this->app['auth']->getToken()] + $query))
					);
				}

				return $handler($request, $options);
			};
		};

		$this->pushMiddleware($middleware, 'access_token');

		return $this;
	}

	/**
	 * 刷新 access_token
	 * @return $this
	 */
	public function withRetryMiddleware()
	{
		if (isset($this->getMiddlewares()['retry'])) {
			return $this;
		}

		$middleware = Middleware::retry(function (
			$retries,
			RequestInterface $request,
			ResponseInterface $response = null
		) {
			if (is_null($response) || $retries < 1) {
				return false;
			}

			if (in_array(json_decode($response->getBody(), true)['errcode'] ?? null, [40001])) {
				$this->app['auth']->refreshToken();
			}

			return true;
		});

		$this->pushMiddleware($middleware, 'retry');

		return $this;
	}
}