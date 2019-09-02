<?php

namespace EasyDingTalk\Kernal;

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
		return $this->request($path, 'GET', ['query' => $query]);
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
		return $this->request($path, 'POST', ['form_params' => $params]);
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
		return $this->request($path, 'POST', ['query' => $query, 'json' => $params]);
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

		return $this->request($path, 'POST', [
			'query'           => $query,
			'multipart'       => $multipart,
			'connect_timeout' => 30,
			'timeout'         => 30,
			'read_timeout'    => 30,
		]);
	}

	/**
	 * @param string $path
	 * @param string $method
	 * @param array  $options
	 *
	 * @return RequestInterface
	 */
	public function request(string $path, string $method = 'GET', array $options = []): RequestInterface
	{
		$method = strtoupper($method);

		$response = $this->app['http_client']->request($method, $path, $options);
		$response = $response->getBody()->rewind();

		$this->app['log']->info('API response:', [
			'Status'  => $response->getStatusCode(),
			'Reason'  => $response->getReasonPhrase(),
			'Headers' => $response->getHeaders(),
			'Body'    => strval($response->getBody()),
		]);

		return $response;
	}
}