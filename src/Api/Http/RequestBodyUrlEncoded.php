<?php

declare(strict_types=1);

namespace SAC\App\Api\Http;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

class RequestBodyUrlEncoded implements RequestBodyInterface
{
    public function __construct(protected array $params)
    {
    }

    public function getBody(): ?StreamInterface
    {
        return empty($this->params) ? null : Utils::streamFor(json_encode($this->params));
    }
}

