<?php

declare(strict_types=1);

namespace SAC\App\Shop;

use SAC\App\Api\Shop;
use SAC\App\Model\Producer;

class Producers
{
    public const string endpoint = '/producers';

    private Shop $api;

    public function __construct(?Shop $api = null)
    {
        $this->api = null === $api ? new Shop() : $api;
    }

    public function getAll(): array
    {
        $response = $this->api->get(static::endpoint);

        $producers = [];
        foreach ($response->getDecodedBody() as $producer) {
            $producers[] = Producer::fromArray($producer);
        }

        return $producers;
    }

    public function create(Producer $producer): void
    {
        $this->api->post(static::endpoint, ['producer' => $producer->toArray()]);
    }
}

