<?php

namespace Rateltalk\DingTalk\Kernal\Contracts;

interface AccessTokenInterface
{
	public function getToken();

	public function refreshToken();
}