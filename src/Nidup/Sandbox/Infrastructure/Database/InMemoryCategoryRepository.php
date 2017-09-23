<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Model\Category;
use Nidup\Sandbox\Domain\Model\CategoryRepository;

class InMemoryCategoryRepository implements CategoryRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Category
    {
        if (!isset($this->items[$code])) {
            throw new \Exception(sprintf("Category %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Category $item)
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
