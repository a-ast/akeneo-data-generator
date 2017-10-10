<?php

namespace Akeneo\Sandbox\Domain\Model;

class AttributeOptions
{
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(AttributeOption $option)
    {
        $this->items[] = $option;
    }

    public function getCodes()
    {
        $codes = [];
        foreach ($this->items as $option) {
            $codes[] = $option->getCode();
        }

        return $codes;
    }
}
