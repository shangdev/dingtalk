<?php

namespace Rateltalk\DingTalK\Kernal\Traits;

use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait InteractsWithCache
{
	/**
	 * @var
	 */
	protected $cache;

	/**
	 * Get cache instance.
	 *
	 * @return Psr16Cache
	 */
	public function getCache()
	{
		if ($this->cache) {
			return $this->cache;
		}

		return $this->cache = $this->createDefaultCache();
	}

	/**
	 * @return Psr16Cache
	 */
	public function createDefaultCache()
	{
		return new Psr16Cache(new FilesystemAdapter('easyDD', 1500));
	}
}