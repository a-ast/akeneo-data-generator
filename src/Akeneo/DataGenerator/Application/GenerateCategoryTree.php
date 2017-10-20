<?php

namespace Akeneo\DataGenerator\Application;

class GenerateCategoryTree
{
    /** @var int */
    private $chidren;
    /** @var int */
    private $level;

    /**
     * @param int $children
     * @param int $level
     */
    public function __construct(int $children, int $level)
    {
        $this->chidren = $children;
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getChildren(): int
    {
        return $this->chidren;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }
}
