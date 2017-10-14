<?php

namespace Akeneo\ApiSandbox\Domain\Model;

use Akeneo\ApiSandbox\Domain\Model\Product\Categories;
use Akeneo\ApiSandbox\Domain\Model\Product\Values;

class Product
{
    private $identifier;
    private $family;
    private $categories;
    private $values;

    public function __construct(string $identifier, Family $family, Values $values, Categories $categories)
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

    public function getCategories(): Categories
    {
        return $this->categories;
    }

    public function getValues(): Values
    {
        return $this->values;
    }
}
