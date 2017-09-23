<?php

namespace Nidup\Sandbox\Domain\Model;

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

    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier,
            'family' => $this->family->getCode(),
            'values' => $this->values->toArray(),
            'categories' => $this->categories->toArray()
        ];
    }
}
