<?php

namespace Nidup\Sandbox\Domain;

class ProductValues
{
    private $values;

    public function __construct()
    {
        $this->values = [];
    }

    public function addValue(ProductValue $value)
    {
        $this->values[] = $value;
    }

    public function toArray()
    {
        // TODO: merge when several per attribute

        $data = [];
        foreach ($this->values as $value) {
            $data = array_merge($data, $value->toArray());
        }

        return $data;
    }
}
