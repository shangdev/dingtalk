<?php

namespace Rateltalk\DingTalk\Kernal;

use Pimple\Container;
use Rateltalk\DingTalk\Kernal\Providers\ConfigServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\LogServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\RequestServiceProvider;
use Rateltalk\DingTalk\Kernal\Providers\HttpClientServiceProvider;

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
	 * @urls https://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html
	 */
	public function getConfig()
	{
		$base = [
			'http' => [
				'timeout'  => 2.0,
				'base_uri' => 'https://oapi.dingtalk.com/',
			],
			'log'  => [
				'default'  => 'dev',
				'channels' => [
					'dev'  => [
						'driver' => 'single',
						'path'   => '/tmp/easyDD.log',
						'level'  => 'debug',
					],
					'prod' => [
						'driver' => 'daily',
						'path'   => '/tmp/easyDD.log',
						'level'  => 'info',
					],
				],
			],
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