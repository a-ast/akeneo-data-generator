<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface AttributeRepository
{
    public function get(string $code): Attribute;
    public function add(Attribute $attribute);
    public function count(): int;
    public function all(): array;
}
