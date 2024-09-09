<?php

namespace Http\Drivers;

use Http\Response;
use \Exception;

use function
    file_get_contents,
    stream_context_create,
    implode,
    explode,
    trim,
    preg_match,
    intval;

class FileGetContents implements DriverInterface
{
    public array $lastRequest;
    public Response|null $lastResponse = null;

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     * @param array|null $data
     * @param array|null $headers
     * @param string|null $user
     * @param string|null $password
     * @param int|null $timeout
     * @return Response
     */
    public function request(string  $method, string $url,
                            ?array  $params = [], ?array $data = [],
                            ?array  $headers = [], ?string $user = null,
                            ?string $password = null, ?int $timeout = null): Response
    {
        $this->lastRequest = $this->options($method, $url, $params, $data, $headers, $user, $password, $timeout);
        $this->lastResponse = $this->make_request($url);
        return $this->lastResponse;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     * @param array|null $data
     * @param array|null $headers
     * @param string|null $user
     * @param string|null $password
     * @param int|null $timeout
     * @return array
     */
    public function options(string  $method, string $url,
                            ?array  $params = [], ?array $data = [],
                            ?array  $headers = [], ?string $user = null,
                            ?string $password = null, ?int $timeout = null): array
    {
        $options['http']['method'] = $method ?: 'GET';
        if ($headers) {
            foreach ($headers as $key => &$value) {
                $value = "$key: $value";
            }
            $options['http']['header'] = implode("\r\n", $headers);
        }
        if ($timeout) {
            $options['http']['timeout'] = $timeout;
        }
        return $options;
    }

    /**
     * @param string $url
     * @return Response
     */
    private function make_request(string $url): Response
    {
        try {
            $body = file_get_contents($url, false, stream_context_create($this->lastRequest));
            $response_headers = $this->parseHeaders($http_response_header);
        } catch (Exception $exception) {
            $body = '';
        }
        return new Response($response_headers['response_code'], $body, $response_headers);
    }

    /**
     * @param array $headers
     * @return array
     */
    private function parseHeaders(?array $headers): array
    {
        $head = array();
        foreach ($headers as $k => $v) {
            $t = explode(':', $v, 2);
            if (isset($t[1]))
                $head[trim($t[0])] = trim($t[1]);
            else {
                $head[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out))
                    $head['response_code'] = intval($out[1]);
            }
        }
        return $head;
    }
}