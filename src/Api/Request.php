<?php

declare(strict_types=1);

namespace SAC\App\Api;

use SAC\App\Api\Authentication\BasicAuth;
use SAC\App\Api\Http\RequestBodyUrlEncoded;

class Request
{
     protected BasicAuth $basicAuth;

     protected ?string $method = null;

     protected ?string $endpoint;

     protected array $headers = [];

     protected array $params = [];

     public function __construct(
         BasicAuth $basicAuth,
         ?string $method = null,
         ?string $endpoint = null,
         array $params = [],
     ) {
         $this->setBasicAuth($basicAuth);
         $this->setMethod($method);
         $this->setEndpoint($endpoint);
         $this->setParams($params);
     }

     public function setBasicAuth(BasicAuth $basicAuth): self
     {
         $this->basicAuth = $basicAuth;

         return $this;
     }

     public function getBasicAuth(): BasicAuth
     {
         return $this->basicAuth;
     }

     public function setMethod(?string $method): void
     {
         if ($method !== null) {
             $this->method = \strtoupper($method);
         }
     }  

     public function getMethod(): ?string
     {
         return $this->method;
     }

     public function validateMethod(): void
     {
         if ($this->method === null || $this->method === '' || $this->method === '0') {
             throw new \RuntimeException('HTTP method not specified.');
         }

         if (!in_array($this->method, ['GET', 'POST', 'DELETE'], true)) {
             throw new \RuntimeException('Invalid HTTP method specified.');
         }
     }

     public function setEndpoint(?string $endpoint): self
     {
         if ($endpoint === null) {
             return $this;
         }

         $this->endpoint = $endpoint;

         return $this;	 
     }

     public function getEndpoint(): ?string
     {
         return $this->endpoint;
     }

     public function getHeaders(): array
     {
         $headers = $this->getDefaultHeaders();

         return \array_merge($this->headers, $headers);
     }

     public function setHeaders(array $headers): void
     {
          $this->headers = \array_merge($this->headers, $headers);
     }

     public function setParams(array $params = []): self
     {
         $this->params = $params;

         return $this;
     }

     public function getParams(): array
     {
         return $this->params;
     }

     public function getPostParams(): array
     {
         if ($this->getMethod() === 'POST') {
             return $this->getParams();
         }

         return [];
     }

     public function getUrl(): string
     {
         $this->validateMethod();

	 return $this->getEndpoint();
     }

     public function getUrlEncodedBody(): RequestBodyUrlEncoded
     {
         return new RequestBodyUrlEncoded($this->getPostParams());
     }

     public function getDefaultHeaders(): array
     {
         return [
             'Authorization' => 'Basic ' . \base64_encode(\sprintf('%s:%s', $this->basicAuth->user, $this->basicAuth->password)),
             'Content-Type' => 'application/json',
	 ];
     }
}

