<?php

namespace EasyDingTalk\Kernal\Utils;

class Colletcion
{
	/**
	 * The collection data.
	 *
	 * @array
	 */
	protected $items = [];

	/**
	 * Set data.
	 *
	 * @param array $items
	 */
	public function __construct(array $items = [])
	{
		foreach ($items as $key => $value) {
			$this->set($key, $value);
		}
	}

	/**
	 * Set the item value.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set($key, $value)
	{
		Arr::set($this->items, $key, $value);
	}

	/**
	 * Retrieve item from Collection.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return Arr::get($this->items, $key, $default);
	}
}