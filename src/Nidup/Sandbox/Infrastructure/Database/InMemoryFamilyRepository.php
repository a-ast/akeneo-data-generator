<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;

class InMemoryFamilyRepository implements FamilyRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Family
    {
        if (!isset($this->items[$code])) {
            throw new \Exception(sprintf("Family %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Family $item)
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
