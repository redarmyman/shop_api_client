<?php

declare(strict_types=1);

namespace SAC\App\Api\Authentication;

class BasicAuth
{
    public string $user;

    public string $password;

    public function __construct(string $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }
}

