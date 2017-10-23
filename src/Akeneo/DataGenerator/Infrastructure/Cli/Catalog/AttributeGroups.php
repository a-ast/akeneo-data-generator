<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class AttributeGroups
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
