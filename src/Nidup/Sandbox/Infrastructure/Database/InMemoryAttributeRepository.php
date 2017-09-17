<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeRepository;

class InMemoryAttributeRepository implements AttributeRepository
{
    private $attributes = [];

    public function __construct()
    {
        $this->attributes = [];
    }

    public function get(string $code): Attribute
    {
        if (!isset($this->attributes[$code])) {
            throw new \Exception(sprintf("Attribute %s does not exists", $code));
        }

        return $this->attributes[$code];
    }

    public function add(Attribute $attribute)
    {
        $this->attributes[$attribute->getCode()] = $attribute;
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    public function all(): array
    {
        return array_values($this->families);
    }
}
