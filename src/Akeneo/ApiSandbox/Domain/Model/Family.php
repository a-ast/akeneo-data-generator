<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class Family
{
    /** @var string */
    private $code;
    /** @var FamilyAttributes */
    private $attributes;
    /** @var FamilyAttributeRequirements */
    private $requirements;

    public function __construct(
        string $code,
        FamilyAttributes $attributes,
        FamilyAttributeRequirements $requirements
    ) {
        $this->code = $code;
        $this->attributes = $attributes;
        $this->requirements = $requirements;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAttributes(): FamilyAttributes
    {
        return $this->attributes;
    }

    public function getRequirements(): FamilyAttributeRequirements
    {
        return $this->requirements;
    }
}
