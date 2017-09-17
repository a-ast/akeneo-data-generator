<?php

namespace Nidup\Sandbox\Domain;

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

    public function toArray()
    {
        $data = [];
        /** @var Category $category */
        foreach ($this->items as $category) {
            $data[] = $category->getCode();
        }

        return $data;
    }
}
