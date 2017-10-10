<?php

namespace Akeneo\Sandbox\Domain\Model;

class ProductValues
{
    private $values;

    public function __construct()
    {
        $this->values = [];
    }

    public function add(ProductValue $value)
    {
        $this->values[] = $value;
    }

    public function all(): array
    {
        return $this->values;
    }
}
