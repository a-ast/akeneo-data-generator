<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeRepository;

class InMemoryAttributeRepository implements AttributeRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Attribute
    {
        if (!isset($this->items[$code])) {
            throw new \Exception(sprintf("Attribute %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Attribute $item)
    {
        $this->items[$item->getCode()] = $item;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function all(): array
    {
        return array_values($this->items);
    }
}
