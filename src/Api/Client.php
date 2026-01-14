<?php

declare(strict_types=1);

namespace SAC\App\Api;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamInterface;

class Client
{
     public const baseUrl = 'http://rekrutacja.localhost:8091/shop_api/v1';

     protected $httpClient;

     public function __construct(
         ?ClientInterface $httpClient = null
     ) {
         $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
     }

     public function prepareRequestMessage(Request $request): array
     {
          $url = static::baseUrl . $request->getUrl();

          return [
              $url,
              $request->getMethod(),
              $request->getHeaders(),
              $request->getUrlEncodedBody()->getBody()
          ]; 
     }

     public function sendRequest(Request $request): Response
     {
         [$url, $method, $headers, $body] = $this->prepareRequestMessage($request);

	 $requestFactory = Psr17FactoryDiscovery::findRequestFactory()->createRequest($method, $url);
	 if ($body instanceof StreamInterface)
	 {
              $requestFactory = $requestFactory->withBody($body);
         }
	 foreach ($headers as $name => $value) {
              $requestFactory = $requestFactory->withHeader($name, $value);
	 }

         $psr7Response = $this->httpClient->sendRequest($requestFactory);

	 $responseHeaders = [];
	 foreach ($psr7Response->getHeaders() as $name => $value) {
              $responseHeaders[] = sprintf('%s: %s', $name, implode(', ', $value));
	 }

         $response = new Response($request, $psr7Response->getBody()->getContents(), $psr7Response->getStatusCode(), $responseHeaders);

	 if ($response->isError()) {
              throw $response->getThrownException();
	 }

	 return $response;
     }
}

