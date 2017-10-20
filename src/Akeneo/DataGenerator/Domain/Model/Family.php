<?php

namespace Akeneo\DataGenerator\Domain\Model;

use Akeneo\DataGenerator\Domain\Model\Family\AttributeRequirements;
use Akeneo\DataGenerator\Domain\Model\Family\Attributes;

class Family
{
    /** @var string */
    private $code;
    /** @var Attributes */
    private $attributes;
    /** @var AttributeRequirements */
    private $requirements;

    public function __construct(
        string $code,
        Attributes $attributes,
        AttributeRequirements $requirements
    ) {
        $this->code = $code;
        $this->attributes = $attributes;
        $this->requirements = $requirements;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    public function getRequirements(): AttributeRequirements
    {
        return $this->requirements;
    }
}