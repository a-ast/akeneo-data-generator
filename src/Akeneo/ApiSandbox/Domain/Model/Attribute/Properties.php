<?php

namespace Akeneo\ApiSandbox\Domain\Model\Attribute;

class Properties
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
