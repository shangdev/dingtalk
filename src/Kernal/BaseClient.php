<?php

namespace Rateltalk\DingTalk\Kernal;

class BaseClient
{
	/**
	 * @var \Rateltalk\DingTalk\Application
	 */
	protected $app;

	/**
	 * @var \Rateltalk\DingTalk\Kernal\Client
	 */
	protected $client;

	/**
	 * BaseClient constructor.
	 *
	 * @param $app
	 */
	public function __construct($app)
	{
		$this->app    = $app;
		$this->client = $this->app['http_client']->withAccessTokenMiddleware()->withRetryMiddleware();
	}
}