<?php

namespace Akeneo\DataGenerator\Application;

class GenerateCategoryTreeWithDefinedTree
{
    private $tree;

    private $children;

    private $level;

    public function __construct(string $tree, int $children, int $level)
    {
        $this->tree = $tree;
        $this->children = $children;
        $this->level = $level;
    }

    public function getTree(): string
    {
        return $this->tree;
    }

    public function getChildren(): int
    {
        return $this->children;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
