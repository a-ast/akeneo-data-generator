<?php

namespace Nidup\Sandbox\Domain;

interface CategoryRepository
{
    public function get(string $code): Category;
    public function add(Category $item);
    public function count(): int;
    public function all(): array;
}
