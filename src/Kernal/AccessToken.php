<?php

namespace Rateltalk\DingTalk\Kernal;

use Rateltalk\DingTalk\Kernal\Contracts\AccessTokenInterface;
use Rateltalk\DingTalk\Kernal\Exceptions\RuntimeException;
use Rateltalk\DingTalk\Kernal\Traits\InteractsWithCache;

class AccessToken implements AccessTokenInterface
{
	use InteractsWithCache;

	/**
	 * @var \Rateltalk\DingTalk\Application $app
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected $requestMethod = 'GET';

	/**
	 * @var
	 */
	protected $endpoint = 'https://oapi.dingtalk.com/gettoken';

	/**
	 * @var string
	 */
	protected $tokenKey = 'access_token';

	/**
	 * @var int
	 */
	protected $safeSeconds = 300;

	/**
	 * @var string
	 */
	protected $cachePrefix = 'dingtalk.access_token.';

	/**
	 * @param \Rateltalk\DingTalk\Application $app
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * @param bool $refresh
	 *
	 * @return array
	 * @throws RuntimeException
	 */
	public function getToken(bool $refresh = false)
	{
		$cacheKey = $this->getCacheKey();
		$cache    = $this->getCache();

		if (!$refresh && $cache->has($cacheKey)) {
			return $cache->get($cacheKey);
		}

		$token = $this->requestToken($this->getCredentials(), true);

		$this->setToken($token[$this->tokenKey], $token['expires_in'] ?? 7200);

		return $token[$this->tokenKey];
	}

	/**
	 * @throws RuntimeException
	 */
	public function refreshToken()
	{
		$this->getToken(true);

		return $this;
	}

	/**
	 * @param string $token
	 * @param int    $expires
	 *
	 * @return $this
	 * @throws RuntimeException
	 */
	public function setToken(string $token, int $expires = 7200)
	{
		$this->getCache()->set($this->getCacheKey(), [
			$this->tokenKey => $token,
			'expires_in'    => $expires,
		], $expires - $this->safeSeconds);

		if (!$this->getCache()->has($this->getCacheKey())) {
			throw new RuntimeException('Failed to cache access token.');
		}

		return $this;
	}

	/**
	 * @param array $credentials
	 * @param bool  $toArray
	 *
	 * @return array
	 */
	public function requestToken(array $credentials, $toArray = false)
	{
		$options = [
			('GET' === $this->requestMethod) ? 'query' : 'json' => $credentials,
		];

		$response = $this->app['http_client']->request($this->requestMethod, $this->endpoint, $options);
		$result   = json_decode($response->getBody()->getContents(), $toArray);

		return $result;
	}

	public function getCacheKey()
	{
		return $this->cachePrefix . md5(json_encode($this->getCredentials()));
	}

	protected function getCredentials()
	{
		return [
			'appkey'    => $this->app['config']['app_key'],
			'appsecret' => $this->app['config']['app_secret'],
		];
	}
}