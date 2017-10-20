<?php

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\MeasureFamily;
use Akeneo\DataGenerator\Domain\Model\MeasureFamilyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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
