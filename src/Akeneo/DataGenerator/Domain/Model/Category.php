<?php

namespace Akeneo\DataGenerator\Domain\Model;

class Category
{
    /** @var string */
    private $code;
    /** @var Category */
    private $parent;
    /** @var Category[] */
    private $children;

    public function __construct(string $code, Category $parent = null)
    {
        $this->code = $code;
        $this->parent = $parent;
        $this->children = [];
    }

    public function code(): string
    {
        return $this->code;
    }

    public function isRoot(): bool
    {
        return $this->parent === null;
    }

    public function addChild(Category $category)
    {
        $this->children[]= $category;
    }

    public function children(): array
    {
        return $this->children;
    }

    public function parent()
    {
        return $this->parent;
    }
}
