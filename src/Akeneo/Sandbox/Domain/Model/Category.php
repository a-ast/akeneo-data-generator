<?php

namespace Akeneo\Sandbox\Domain\Model;

class Category
{
    /** @var string */
    private $code;
    /** @var Category */
    private $parent;

    public function __construct(string $code, Category $parent = null)
    {
        $this->code = $code;
        $this->parent = $parent;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isRoot(): bool
    {
        return $this->parent === null;
    }
}
