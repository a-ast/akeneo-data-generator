<?php

namespace Akeneo\ApiSandbox\Domain\Model;

use Akeneo\ApiSandbox\Domain\Model\Attribute\Properties;
use Akeneo\ApiSandbox\Domain\Model\Attribute\Options;

class Attribute
{
    private $code;
    private $type;
    private $localizable;
    private $scopable;
    private $properties;
    private $options;
    private $group;

    public function __construct(
        string $code,
        string $type,
        bool $localizable,
        bool $scopable,
        Properties $properties,
        Options $options,
        AttributeGroup $group
    ) {
        $this->code = $code;
        $this->type = $type;
        $this->localizable = $localizable;
        $this->scopable = $scopable;
        $this->properties = $properties;
        $this->options = $options;
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
     * @return Properties
     */
    public function getProperties(): Properties
    {
        return $this->properties;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @return AttributeGroup
     */
    public function getGroup(): AttributeGroup
    {
        return $this->group;
    }
}
