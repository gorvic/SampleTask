<?php

declare(strict_types=1);

namespace Http;

use Http\Drivers\DriverInterface as HttpDriver;
use Http\Drivers\FileGetContents;
use Http\Response as HttpResponse;
use function
    getenv,
    array_key_exists,
    parse_url;

class Request
{
    private string $_host;
    private string $_path;
    private array $_headers = [];

    public function __construct(public string|null         $username = null,
                                public string|null         $password = null,
                                private HttpDriver         $httpDriver = new FileGetContents(),
                                private array|false|string $environment = false)
    {
        $this->environment = $environment ?: getenv();
    }

    /**
     * Makes a request  using the configured http client
     * Authentication information is automatically added if none is provided
     *
     * @param string $method HTTP Method
     * @param string $uri Fully qualified url
     * @param string[] $params Query string parameters
     * @param string[] $data POST body data
     * @param string[] $headers HTTP Headers
     * @param string|null $username User for Authentication
     * @param string|null $password Password for Authentication
     * @param int|null $timeout Timeout in seconds
     * @return HttpResponse Response from the API
     */
    public function request(string  $method, string $uri,
                            ?array  $params = [], ?array $data = [],
                            ?array  $headers = [], ?string $username = null,
                            ?string $password = null, ?int $timeout = null): HttpResponse
    {
        $username = $username ?: $this->username;
        $password = $password ?: $this->password;

        foreach ($this->_headers as $key => $value) {
            if (!array_key_exists($key, $headers)) {
                $headers[$key] = $value;
            }
        }

        $headers['User-Agent'] = 'abstract-php-client/1.0' .
            ' (PHP ' . PHP_VERSION . ')';
        $headers['Accept-Charset'] = 'utf-8';

        if ($method === 'POST' && !array_key_exists('Content-Type', $headers)) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        if (!array_key_exists('Accept', $headers)) {
            $headers['Accept'] = 'application/json';
        }

        $uri = $this->buildUri($uri);

        return $this->getHttpDriver()->request(
            $method,
            $uri,
            $params,
            $data,
            $headers,
            $username,
            $password,
            $timeout
        );
    }

    /**
     * Build the final request uri
     *
     * @param string $uri The original request uri
     * @return string BinRequest uri
     */
    public function buildUri(string $uri): string
    {
        return isset(parse_url($uri)['host']) ? $uri : $this->getHost() . $this->getPath() . $uri;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->_host;
    }

    /**
     * @param string $host
     * @return Request
     */
    public function setHost(string $host): self
    {
        $this->_host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->_path;
    }

    /**
     * @param string $path
     * @return Request
     */
    public function setPath(string $path): self
    {
        $this->_path = $path;
        return $this;
    }

    /**
     * Retrieve the HttpClient
     *
     * @return HttpDriver Current HttpClient
     */
    public function getHttpDriver(): HttpDriver
    {
        return $this->httpDriver;
    }

    /**
     * Sets new HttpClient
     *
     * @param HttpDriver $httpDriver
     * @return $this
     */
    public function setHttpDriver(HttpDriver $httpDriver): self
    {
        $this->httpDriver = $httpDriver;
        return $this;
    }

    /**
     * @param array $headers
     * @return Request
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $key => $value) {
            $this->_headers[$key] = $value;
        }
        return $this;
    }
}