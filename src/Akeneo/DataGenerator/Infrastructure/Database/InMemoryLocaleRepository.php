<?php

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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

    public function allEnabled(): array
    {
        $enabledLocales = [];
        /**
         * @var Locale $locale
         */
        foreach ($this->items as $locale) {
            if ($locale->enabled()) {
                $enabledLocales[]= $locale;
            }
        }

        return $enabledLocales;
    }
}
