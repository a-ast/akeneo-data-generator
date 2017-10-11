<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class Family
{
    /** @var string */
    private $code;
    /** @var Attribute[] */
    private $attributes;

    public function __construct(string $code, array $attributes)
    {
        $this->code = $code;
        $this->attributes = $attributes;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
