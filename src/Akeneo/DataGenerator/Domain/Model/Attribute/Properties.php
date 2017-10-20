<?php

namespace Akeneo\DataGenerator\Domain\Model\Attribute;

class Properties
{
    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function all():array
    {
        return $this->properties;
    }

    /**
     * @return mixed|null
     */
    public function getProperty(string $code)
    {
        return $this->properties[$code];
    }
}
