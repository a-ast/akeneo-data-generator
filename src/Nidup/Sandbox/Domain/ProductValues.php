<?php

namespace Nidup\Sandbox\Domain;

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

    public function toArray()
    {
        $data = [];
        /** @var ProductValue $value */
        foreach ($this->values as $value) {

            if (!isset($data[$value->getAttribute()->getCode()])) {
                $data[$value->getAttribute()->getCode()] = [];
            }
            $data[$value->getAttribute()->getCode()][] = [
                'data' => $value->getData(),
                'locale' => $value->getLocale(),
                'scope' => $value->getChannel(),
            ];
        }

        return $data;
    }
}
