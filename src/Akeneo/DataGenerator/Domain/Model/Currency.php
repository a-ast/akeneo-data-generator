<?php

namespace Akeneo\DataGenerator\Domain\Model;

class Currency
{
    /** @var string */
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function code(): string
    {
        return $this->code;
    }
}
