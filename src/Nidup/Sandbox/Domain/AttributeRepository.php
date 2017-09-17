<?php

namespace Nidup\Sandbox\Domain;

interface AttributeRepository
{
    public function get(string $code): Attribute;
    public function add(Attribute $attribute);
    public function count(): int;
    public function all(): array;
}
