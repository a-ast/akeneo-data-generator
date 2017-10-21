<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class Attributes
{
    private $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function count(): int
    {
        return $this->count;
    }
}
