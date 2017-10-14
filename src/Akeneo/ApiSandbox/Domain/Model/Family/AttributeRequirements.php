<?php

namespace Akeneo\ApiSandbox\Domain\Model\Family;

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
            if (!isset($codes[$requirement->getChannel()->getCode()])) {
                $codes[$requirement->getChannel()->getCode()] = [];
            }
            $codes[$requirement->getChannel()->getCode()][] = $requirement->getAttribute()->getCode();
        }

        return $codes;
    }
}
