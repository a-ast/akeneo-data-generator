<?php

namespace Nidup\Sandbox\Domain;

class Attribute
{
    private $code;
    private $type;
    private $localizable;
    private $scopable;
    private $properties;

    public function __construct(
        string $code,
        string $type,
        bool $localizable,
        bool $scopable,
        AttributeProperties $properties
    ) {
        $this->code = $code;
        $this->type = $type;
        $this->localizable = $localizable;
        $this->scopable = $scopable;
        $this->properties = $properties;
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
}
