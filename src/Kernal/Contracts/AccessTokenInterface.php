<?php

namespace EasyDingTalk\Kernal\Contracts;

interface AccessTokenInterface
{
	public function getToken();

	public function refreshToken();
}