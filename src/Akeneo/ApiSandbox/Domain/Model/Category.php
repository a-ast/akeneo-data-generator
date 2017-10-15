<?php

namespace Akeneo\ApiSandbox\Domain\Model;

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

    public function getCode(): string
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

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
