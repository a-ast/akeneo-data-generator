<?php

namespace Akeneo\DataGenerator\Domain\Model;

use Akeneo\DataGenerator\Domain\Model\Attribute\Properties;
use Akeneo\DataGenerator\Domain\Model\Attribute\Options;

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
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function localizable(): bool
    {
        return $this->localizable;
    }

    /**
     * @return bool
     */
    public function scopable(): bool
    {
        return $this->scopable;
    }

    /**
     * @return Properties
     */
    public function properties(): Properties
    {
        return $this->properties;
    }

    /**
     * @return Options
     */
    public function options(): Options
    {
        return $this->options;
    }

    /**
     * @return AttributeGroup
     */
    public function group(): AttributeGroup
    {
        return $this->group;
    }
}
