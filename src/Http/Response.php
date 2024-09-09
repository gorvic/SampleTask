<?php

declare(strict_types=1);

namespace Http;

use stdClass;
use function
    json_decode,
    json_validate;

class Response
{
    protected ?array $headers;
    protected ?string $content;
    protected ?int $statusCode;

    public function __construct(?int $statusCode, ?string $content, ?array $headers = []) {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @return stdClass
     */
    public function getJsonContent(): array
    {
        return $this->isAccepted() && json_validate($this->content)
            ? json_decode($this->content, true)
            : [];
    }

    /**
     * @return int
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool {
        return $this->getStatusCode() < 400;
    }

    public function __toString(): string {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->content;
    }
}