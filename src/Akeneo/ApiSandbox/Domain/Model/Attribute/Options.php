<?php

namespace Akeneo\ApiSandbox\Domain\Model\Attribute;

use Akeneo\ApiSandbox\Domain\Model\Attribute\Option;

class Options
{
    /** @var array */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(Option $option)
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
