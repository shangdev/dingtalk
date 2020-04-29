<?php

namespace Rateltalk\DingTalk\Kernal;

use Psr\Http\Message\RequestInterface;

class BaseClient
{
	/**
	 * @var ServiceContainer
	 */
	protected $app;

	/**
	 * Constructor.
	 *
	 * @param ServiceContainer $app
	 */
	public function __construct(ServiceContainer $app)
	{
		$this->app = $app;
	}

	/**
	 * Get request.
	 *
	 * @param string $path
	 * @param array  $query
	 *
	 * @return mixed
	 */
	public function httpGet(string $path, array $query = [])
	{
		return $this->request('GET', $path, ['query' => $query]);
	}

	/**
	 * Post request.
	 *
	 * @param string $path
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function httpPost(string $path, array $params = [])
	{
		return $this->request('POST', $path, ['form_params' => $params]);
	}

	/**
	 * Post json request.
	 *
	 * @param string $path
	 * @param array  $query
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function httpPostJson(string $path, array $query = [], array $params = [])
	{
		return $this->request('POST', $path, ['query' => $query, 'json' => $params]);
	}

	/**
	 * Upload request.
	 *
	 * @param string $path
	 * @param array  $query
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function httpUpload(string $path, array $query = [], array $params = [])
	{
		$multipart = [];

		foreach ($params as $param) {
			$multipart[] = [
				'name'     => $param['name'],
				'contents' => $param['content'],
			];
		}

		return $this->request('POST', $path, [
			'query'           => $query,
			'multipart'       => $multipart,
			'connect_timeout' => 30,
			'timeout'         => 30,
			'read_timeout'    => 30,
		]);
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param array  $options
	 *
	 * @return RequestInterface
	 */
	public function request(string $method, string $path, array $options = []): RequestInterface
	{
		$method = strtoupper($method);

		$response = $this->app['http_client']->request($method, $path, $options);
		$response = $response->getBody()->rewind();

		// 使用 monolog context 传递一个数组格式的数据
		$this->app['logger']->info('API response', [
			'statusCode'      => $response->getStatusCode(), // 状态码
			'reasonPhrase'    => $response->getReasonPhrase(), // 原因短语
			'headers'         => $response->getHeaders(), // 头信息
			'body'            => $response->getBody(), // 主题部分
			'protocolVersion' => $response->getProtocolVersion(), // 协议
		]);

		return $response;
	}
}