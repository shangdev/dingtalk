<?php

namespace Rateltalk\DingTalk;

use Pimple\Container;
use Rateltalk\DingTalk\Kernal\Providers\AccessTokenServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\ConfigServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\HttpClientServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\LogServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\RequestServiceProvider;

class Application extends Container
{
    /**
     * @property \Rateltalk\Dingtalk\Robot\Client $robot
     */
    protected $providers = [
    	Robot\ServiceProvider::class,
	];

	/**
	 * @var array
	 */
	protected $userConfig = [];

	/**
	 * Application constructor.
	 *
	 * @param array $config
	 * @param array $values
	 */
	public function __construct(array $config = [], array $values = [])
	{
		$this->registerProviders($this->getProviders());

		$this->userConfig = $config;

		parent::__construct($values);
	}

	/**
	 * @return array
	 * @url https://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html
	 */
	public function getConfig()
	{
		$base = [
			'http' => [
				'timeout'  => 30.0,
				'base_uri' => 'https://oapi.dingtalk.com/',
			]
		];

		return array_replace_recursive($base, $this->userConfig);
	}

	/**
	 * Return all providers.
	 */
	public function getProviders()
	{
		return array_merge([
			ConfigServiceProvider::class,
			AccessTokenServiceProvider::class,
			LogServiceProvider::class,
			HttpClientServiceProvider::class,
			RequestServiceProvider::class,
		], $this->providers);
	}

	/**
	 * @param array $providers
	 */
	public function registerProviders(array $providers)
	{
		foreach ($providers as $provider) {
			parent::register(new $provider());
		}
	}
}