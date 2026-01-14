<?php

declare(strict_types=1);

namespace SAC\App\Api;

use InvalidArgumentException;
use Psr\Http\Client\ClientInterface;
use RuntimeException;
use SAC\App\Api\Authentication\BasicAuth;

class Shop
{
    final public const string BasicAuthUserEnvName = 'SHOP_BASIC_AUTH_USER';

    final public const string BasicAuthPassEnvName = 'SHOP_BASIC_AUTH_PASS';

    protected BasicAuth $basicAuth;

    protected Client $client;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'basic_auth_user' => getenv(static::BasicAuthUserEnvName),
            'basic_auth_pass' => getenv(static::BasicAuthPassEnvName),
            'http_client' => null,
        ], $config);

        if (!$config['basic_auth_user']) {
            throw new RuntimeException('Required "basic_auth_user" key not supplied');
        }
        if (!$config['basic_auth_pass']) {
            throw new RuntimeException('Required "basic_auth_pass" key not supplied');
        }
        if ($config['http_client'] !== null && !$config['http_client'] instanceof ClientInterface) {
            throw new InvalidArgumentException('Required "http_client" key have to be instance of \Psr\Http\Client\ClientInterface');
        }

	$this->basicAuth = new BasicAuth($config['basic_auth_user'], $config['basic_auth_pass']);
	$this->client = new Client($config['http_client']);
    }

    public function getBasicAuth(): BasicAuth
    {
        return $this->basicAuth;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function get(string $endpoint,): Response
    {
        return $this->sendRequest('GET', $endpoint, []);
    }

    public function post(string $endpoint, array $params = []): Response
    {
        return $this->sendRequest('POST', $endpoint, $params);
    }

    public function sendRequest(
        string $method,
        string $endpoint,
	array $params = [],
    ): Response {
        $request = $this->request($method, $endpoint, $params);

        return $this->client->sendRequest($request);
    }

    public function request(
        string $method,
        string $endpoint,
        array $params = [],
    ): Request {
        return new Request($this->basicAuth, $method, $endpoint, $params);
    }
}

