<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface CategoryRepository
{
    public function get(string $code): Category;
    public function add(Category $item);
    public function count(): int;
    public function all(): array;
    public function countChildren(): int;
    public function allChildren(): array;
}
