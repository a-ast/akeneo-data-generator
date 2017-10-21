<?php

namespace Akeneo\DataGenerator\Domain\Model;

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
    public function code(): string
    {
        return $this->code;
    }
}
