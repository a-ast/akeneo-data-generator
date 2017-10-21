<?php

namespace Akeneo\DataGenerator\Domain\Model;

use Akeneo\DataGenerator\Domain\Model\Product\Categories;
use Akeneo\DataGenerator\Domain\Model\Product\Values;

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

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function family()
    {
        return $this->family;
    }

    public function categories(): Categories
    {
        return $this->categories;
    }

    public function values(): Values
    {
        return $this->values;
    }
}
