<?php

declare(strict_types=1);

namespace SAC\App\Api\Http;

use Psr\Http\Message\StreamInterface;

interface RequestBodyInterface
{
    public function getBody(): ?StreamInterface;
}

