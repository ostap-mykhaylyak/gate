<?php

namespace Ostap\Gate;

class Config
{
    private $timeout;
    private $retryAttempts;
    private $port;

    public function __construct(int $timeout = 10, int $retryAttempts = 3, int $port = 22)
    {
        $this->timeout = $timeout;
        $this->retryAttempts = $retryAttempts;
        $this->port = $port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getRetryAttempts(): int
    {
        return $this->retryAttempts;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
