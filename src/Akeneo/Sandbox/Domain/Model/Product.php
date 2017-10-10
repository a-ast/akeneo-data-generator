<?php

namespace Akeneo\Sandbox\Domain\Model;

class Product
{
    private $identifier;
    private $family;
    private $categories;
    private $values;

    public function __construct(string $identifier, Family $family, ProductValues $values, ProductCategories $categories)
    {
        $this->identifier = $identifier;
        $this->family = $family;
        $this->values = $values;
        $this->categories = $categories;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getFamily()
    {
        return $this->family;
    }

    public function getCategories(): ProductCategories
    {
        return $this->categories;
    }

    public function getValues(): ProductValues
    {
        return $this->values;
    }
}
