<?php

declare(strict_types=1);

namespace SAC\App\Api;

use SAC\App\Api\Exception\ResponseException;

class Response
{
    protected array $decodedBody = [];
    protected ?ResponseException $thrownException = null;

    public function __construct(
        protected Request $request,
        protected ?string $body = null,
        protected ?int $httpStatusCode = null,
        protected array $headers = [],
    ) {
        $this->decodeBody();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): ?string
    { 
        return $this->body;
    }

    public function getDecodedBody(): array
    {
        return $this->decodedBody;
    }

    public function isError(): bool
    {
        return isset($this->decodedBody['error']);
    }

    public function throwException(): never
    {
        throw $this->thrownException;
    }

    public function makeException(): void
    {
        $this->thrownException = new ResponseException($this);
    }

    public function getThrownException(): ?ResponseException
    {
        return $this->thrownException;
    }

    public function decodeBody(): void
    {
        if ($this->body === null) {
            $this->decodedBody = [];
	} else {
            try {
                if ($this->httpStatusCode !== 200 && $this->httpStatusCode !== 201) {
                    $this->decodedBody = ['error' => $this->body];
                } else {
                    $decodedBody = \json_decode($this->body, true, flags: JSON_THROW_ON_ERROR);
 
                    $this->decodedBody = is_string($decodedBody)
                        ? ['error' => $decodedBody]
                        : $decodedBody;
                }
            } catch (\JsonException) {
                $this->decodedBody = ['error' => $this->body];
            }
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }
}

