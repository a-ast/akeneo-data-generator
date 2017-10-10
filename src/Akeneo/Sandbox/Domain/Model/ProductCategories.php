<?php

namespace Akeneo\Sandbox\Domain\Model;

class ProductCategories
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
