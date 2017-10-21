<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class Families
{
    private $count;
    private $attributesCount;

    public function __construct(int $count, int $attributesCount)
    {
        $this->count = $count;
        $this->attributesCount = $attributesCount;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function attributesCount(): int
    {
        return $this->attributesCount;
    }
}
