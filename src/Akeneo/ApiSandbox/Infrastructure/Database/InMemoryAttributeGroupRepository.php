<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\AttributeGroup;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;

class InMemoryAttributeGroupRepository implements AttributeGroupRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): AttributeGroup
    {
        if (!isset($this->items[$code])) {
            throw new EntityDoesNotExistsException(sprintf("Attribute Group %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(AttributeGroup $item)
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
