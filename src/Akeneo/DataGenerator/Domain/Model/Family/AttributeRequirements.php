<?php

namespace Akeneo\DataGenerator\Domain\Model\Family;

class AttributeRequirements
{
    /** @var array */
    private $items;

    public function __construct(array $requirements)
    {
        $this->items = $requirements;
    }

    public function getAttributeCodesPerChannel(): array
    {
        $codes = [];
        foreach ($this->items as $requirement) {
            if (!isset($codes[$requirement->getChannel()->code()])) {
                $codes[$requirement->getChannel()->code()] = [];
            }
            $codes[$requirement->getChannel()->code()][] = $requirement->getAttribute()->code();
        }

        return $codes;
    }
}
