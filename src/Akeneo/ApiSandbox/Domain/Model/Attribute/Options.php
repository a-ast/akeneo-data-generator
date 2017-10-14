<?php

namespace Akeneo\ApiSandbox\Domain\Model\Attribute;

class Options
{
    /** @var array */
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function getCodes():array
    {
        $codes = [];
        foreach ($this->items as $option) {
            $codes[] = $option->getCode();
        }

        return $codes;
    }
}
