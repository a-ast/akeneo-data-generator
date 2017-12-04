<?php

namespace Akeneo\DataGenerator\Domain\Model;

interface AttributeRepository
{
    public function get(string $code): Attribute;
    public function add(Attribute $attribute);
    public function addAll(array $attributes);
    public function count(): int;
    public function all(): array;
}
