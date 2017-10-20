<?php

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

class InMemoryCurrencyRepository implements CurrencyRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Currency
    {
        if (!isset($this->items[$code])) {
            throw new EntityDoesNotExistsException(sprintf("Currency %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Currency $item)
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
