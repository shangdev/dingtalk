<?php

namespace Rateltalk\DingTalk;

use Pimple\Container;
use Rateltalk\DingTalk\Kernal\Utils\Collection;

class Application extends Container
{
    /**
     * @property \Rateltalk\Dingtalk\Robot\Client $robot
     */
    protected $providers = [
    	Robot\ServiceProvider::class,
		Kernal\Providers\ConfigServiceProvider::class,
		Kernal\Providers\LogServiceProvider::class,
		Kernal\Providers\HttpClientServiceProvider::class,
		Kernal\Providers\RequestServiceProvider::class,
	];

	/**
	 * Application constructor.
	 *
	 * @param array $config
	 * @param array $values
	 */
	public function __construct(array $config = [], array $values = [])
	{
		parent::__construct($values);

		$this['config'] = function () use ($config) {
			return new Collection($config);
		};

		foreach ($this->providers as $provider) {
			parent::register(new $provider());
		}
	}
}