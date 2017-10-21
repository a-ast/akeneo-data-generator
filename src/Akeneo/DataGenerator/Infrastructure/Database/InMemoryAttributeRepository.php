<?php

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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
            throw new EntityDoesNotExistsException(sprintf("Attribute %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Attribute $item)
    {
        $this->items[$item->code()] = $item;
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
