<?php

namespace Http\Drivers;

use Http\Response;

interface DriverInterface {
    public function request(string $method, string $url,
                            ?array $params = [], ?array $data = [], ?array $headers = [],
                            ?string $user = null, ?string $password = null,
                            ?int $timeout = null): Response;
    public function options(string $method, string $url,
                            ?array $params = [], ?array $data = [], ?array $headers = [],
                            ?string $user = null, ?string $password = null,
                            ?int $timeout = null): array;
}