<?php

namespace Nidup\Sandbox\Domain;

class AttributeOption
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
