<?php

namespace Akeneo\ApiSandbox\Domain\Model\Product;

class Values
{
    private $values;

    public function __construct()
    {
        $this->values = [];
    }

    public function add(Value $value)
    {
        $this->values[] = $value;
    }

    public function all(): array
    {
        return $this->values;
    }
}
