<?php

namespace EasyDingTalk\Kernal;

use EasyDingTalk\Kernal\Contracts\AccessTokenInterface;
use EasyDingTalk\Kernal\Traits\InteractsWithCache;

abstract class AccessToken implements AccessTokenInterface
{
	use InteractsWithCache;

	/**
	 * @var ServiceContainer
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected $requestMethod = 'GET';

	/**
	 * @var
	 */
	protected $endpoint;

	/**
	 * @var string
	 */
	protected $cachePrefix = 'easyDD.kernal.access_token.';

	/**
	 * @param ServiceContainer $app
	 */
	public function __construct(ServiceContainer $app)
	{
		$this->app = $app;
	}

	/**
	 * Credential for get token.
	 *
	 * @return array
	 */
	abstract protected function getCredential(): array;

	/**
	 * @param bool $refresh
	 *
	 * @return array
	 */
	public function getToken(bool $refresh = false)
	{
		$cacheKey = $this->getCacheKey();
		$cache = $this->getCache();

		if (!$refresh && $cache->has($cacheKey)) {
			return $cache->get($cacheKey);
		}

		$token = $this->requestToken($this->getCredential(), true);

		return $token;
	}

	/**
	 * @param string $token
	 * @param int    $expires
	 */
	public function setToken(string $token, int $expires = 7200)
	{

	}

	public function refreshToken()
	{
		$this->getToken(true);

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
		$result = json_decode($response->getBody()->getContents(), $toArray);

		return $result;
	}

	protected function getCacheKey()
	{
		return $this->cachePrefix . md5(json_encode($this->getCredential()));
	}
}