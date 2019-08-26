<?php

namespace EasyDingTalk\Kernal\Utils;

class Arr
{
	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param array  $arr
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function exists(array $arr, $key)
	{
		return array_key_exists($key, $arr);
	}

	/**
	 * @param array  $arr
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return array
	 */
	public static function set(array &$arr, string $key, $value): array
	{
		$keys = explode('.', $key);

		while (count($keys) > 1) {
			$key = array_shift($keys);

			if (!isset($arr[$key]) || !is_array($arr[$key])) {
				$arr[$key] = [];
			}

			$arr = &$arr[$key];
		}

		$arr[array_shift($keys)] = $value;

		return $arr;
	}

	/**
	 * Get an item from an array using 'dot' notation.
	 *
	 * @param array  $arr
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public static function get(array $arr, string $key, $default = null)
	{
		if (static::exists($arr, $key)) {
			return $arr[$key];
		}

		foreach (explode('.', $key) as $segment) {
			if (static::exists($arr, $segment)) {
				$arr = $arr[$segment];
			} else {
				return $default;
			}
		}

		return $arr;
	}
}