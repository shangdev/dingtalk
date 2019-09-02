<?php

namespace EasyDingTalk\Kernal;

use Pimple\Container;
use EasyDingTalk\Kernal\Providers\ConfigServiceProvider;
use EasyDingTalk\Kernal\Providers\LogServiceProvider;
use EasyDingTalk\Kernal\Providers\RequestServiceProvider;
use EasyDingTalk\Kernal\Providers\HttpClientServiceProvider;

class ServiceContainer extends Container
{
	/**
	 * @var array
	 */
	protected $providers = [];

	/**
	 * @var array
	 */
	protected $userConfig = [];

	/**
	 * Constructor.
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
	 */
	public function getConfig()
	{
		$base = [
			// https://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html
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