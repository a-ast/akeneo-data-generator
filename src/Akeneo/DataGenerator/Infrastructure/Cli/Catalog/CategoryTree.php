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

    /**
     * @return int
     */
    public function getChildren(): int
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getLevels(): int
    {
        return $this->levels;
    }
}
