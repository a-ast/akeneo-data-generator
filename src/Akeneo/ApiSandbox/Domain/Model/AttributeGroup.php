<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class AttributeGroup
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
