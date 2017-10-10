<?php

namespace Akeneo\Sandbox\Infrastructure\Database;

use Akeneo\Sandbox\Domain\Model\Family;
use Akeneo\Sandbox\Domain\Model\FamilyRepository;

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
