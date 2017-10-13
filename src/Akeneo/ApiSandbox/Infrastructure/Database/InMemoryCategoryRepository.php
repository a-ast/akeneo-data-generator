<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Domain\Model\CategoryRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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
            throw new EntityDoesNotExistsException(sprintf("Category %s does not exists", $code));
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

    public function countChildren(): int
    {
        return count($this->allChildren());
    }

    public function allChildren(): array
    {
        $children = [];
        foreach ($this->items as $category) {
            if (!$category->isRoot()) {
                $children[] = $category;
            }
        }

        return $children;
    }
}
