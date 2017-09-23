<?php

namespace Nidup\Sandbox\Domain\Model;

class AttributeProperties
{
    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return mixed|null
     */
    public function getProperty(string $code)
    {
        return $this->properties[$code];
    }
}
