<?php

namespace EasyDingTalk\Kernal;

use EasyDingTalk\Kernal\Contracts\AccessTokenInterface;
use EasyDingTalk\Kernal\Exceptions\RuntimeException;
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
	protected $tokenKey = 'access_token';

	/**
	 * @var int
	 */
	protected $safeSeconds = 300;

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

		return $token;
	}

	/**
	 * @param string $token
	 * @param int    $expires
	 *
	 * @return AccessTokenInterface
	 * @throws RuntimeException
	 */
	public function setToken(string $token, int $expires = 7200): AccessTokenInterface
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
	 * @return AccessTokenInterface
	 * @throws RuntimeException
	 */
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
		$result   = json_decode($response->getBody()->getContents(), $toArray);

		return $result;
	}

	protected function getCacheKey()
	{
		return $this->cachePrefix . md5(json_encode($this->getCredentials()));
	}

	/**
	 * Credential for get token.
	 *
	 * @return array
	 */
	abstract protected function getCredentials(): array;
}