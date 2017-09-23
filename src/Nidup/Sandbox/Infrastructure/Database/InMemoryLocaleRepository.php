<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Model\Locale;
use Nidup\Sandbox\Domain\Model\LocaleRepository;

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
            throw new \Exception(sprintf("Locale %s does not exists", $code));
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
