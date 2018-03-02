<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class CategoryTree
{
    private $code;
    private $children;
    private $levels;

    public function __construct(string $code, int $children, int $levels)
    {
        $this->code = $code;
        $this->children = $children;
        $this->levels = $levels;
    }

    public function getChildren(): int
    {
        return $this->children;
    }

    public function getLevels(): int
    {
        return $this->levels;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
