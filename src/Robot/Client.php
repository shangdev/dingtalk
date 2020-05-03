<?php

namespace Rateltalk\DingTalk\Robot;

use Rateltalk\DingTalk\Kernal\BaseClient;
use Rateltalk\DingTalk\Kernal\Exceptions\InvalidArgumentException;

class Client extends BaseClient
{
	/**
	 * 消息发送
	 *
	 * @param array $message
	 *
	 * @return mixed
	 * @throws InvalidArgumentException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function send($message = [])
	{
		if (empty($message)) {
			throw new InvalidArgumentException('缺少必要的参数。');
		}

		return $this->client->postJson('robot/send', $message);
	}
}