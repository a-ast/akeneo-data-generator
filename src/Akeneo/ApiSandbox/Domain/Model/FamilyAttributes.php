<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class FamilyAttributes
{
    /** @var array */
    private $items;

    public function __construct(array $attributes)
    {
        $this->items = $attributes;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function getCodes(): array
    {
        $codes = [];
        foreach ($this->items as $attribute) {
            $codes[] = $attribute->getCode();
        }

        return $codes;
    }
}
