<?php

namespace Akeneo\DataGenerator\Domain\Model\Product;

use Akeneo\DataGenerator\Domain\Model\Category;

class Categories
{
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function add(Category $category)
    {
        $this->items[] = $category;
    }

    public function all(): array
    {
        return $this->items;
    }
}
