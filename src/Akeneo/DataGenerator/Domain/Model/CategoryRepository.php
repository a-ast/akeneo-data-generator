<?php

namespace Akeneo\DataGenerator\Domain\Model;

interface CategoryRepository
{
    public function get(string $code): Category;
    public function add(Category $item);
    public function upsert(Category $item);
    public function upsertMany(array $item): void;
    public function count(): int;
    public function all(): array;
    public function countChildren(): int;
    public function allChildren(): array;
    public function countTrees(): int;
    public function allTrees(): array;
}
