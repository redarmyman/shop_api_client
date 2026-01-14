<?php

declare(strict_types=1);

namespace SAC\App\Api\Exception;

use SAC\App\Api\Response;

class ResponseException extends \Exception
{
    private int $httpCode;

    public function __construct(Response $response)
    {
	$this->httpCode = $response->getHttpStatusCode();

        parent::__construct($response->getDecodedBody()['error']);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}

