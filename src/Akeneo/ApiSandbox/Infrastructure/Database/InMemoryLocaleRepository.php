<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Locale;
use Akeneo\ApiSandbox\Domain\Model\LocaleRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;

class InMemoryLocaleRepository implements LocaleRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Locale
    {
        if (!isset($this->items[$code])) {
            throw new EntityDoesNotExistsException(sprintf("Locale %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Locale $item)
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
