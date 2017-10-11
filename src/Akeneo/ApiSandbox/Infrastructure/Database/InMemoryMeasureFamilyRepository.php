<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\MeasureFamily;
use Akeneo\ApiSandbox\Domain\Model\MeasureFamilyRepository;

class InMemoryMeasureFamilyRepository implements MeasureFamilyRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): MeasureFamily
    {
        if (!isset($this->items[$code])) {
            throw new \Exception(sprintf("MeasureFamily %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(MeasureFamily $item)
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
