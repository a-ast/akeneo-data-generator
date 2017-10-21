<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class Products
{
    private $count;
    private $withImages;

    public function __construct(int $count, bool $withImages)
    {
        $this->count = $count;
        $this->withImages = $withImages;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function withImages(): bool
    {
        return $this->withImages;
    }
}
