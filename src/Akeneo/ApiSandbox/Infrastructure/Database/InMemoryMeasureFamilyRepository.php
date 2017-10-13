<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\MeasureFamily;
use Akeneo\ApiSandbox\Domain\Model\MeasureFamilyRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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
            throw new EntityDoesNotExistsException(sprintf("Measure Family %s does not exists", $code));
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
