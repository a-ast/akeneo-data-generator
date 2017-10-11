<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class Currency
{
    /** @var string */
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
