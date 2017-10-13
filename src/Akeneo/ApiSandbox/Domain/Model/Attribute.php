<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class Attribute
{
    private $code;
    private $type;
    private $localizable;
    private $scopable;
    private $properties;
    private $attributeOptions;
    private $group;

    public function __construct(
        string $code,
        string $type,
        bool $localizable,
        bool $scopable,
        AttributeProperties $properties,
        AttributeOptions $attributeOptions,
        AttributeGroup $group
    ) {
        $this->code = $code;
        $this->type = $type;
        $this->localizable = $localizable;
        $this->scopable = $scopable;
        $this->properties = $properties;
        $this->attributeOptions = $attributeOptions;
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isLocalizable(): bool
    {
        return $this->localizable;
    }

    /**
     * @return bool
     */
    public function isScopable(): bool
    {
        return $this->scopable;
    }

    /**
     * @return AttributeProperties
     */
    public function getProperties(): AttributeProperties
    {
        return $this->properties;
    }

    /**
     * @return AttributeOptions
     */
    public function getAttributeOptions(): AttributeOptions
    {
        return $this->attributeOptions;
    }

    /**
     * @return AttributeGroup
     */
    public function getAttributeGroup(): AttributeGroup
    {
        return $this->group;
    }
}
