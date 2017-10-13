<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface AttributeGroupRepository
{
    public function get(string $code): AttributeGroup;
    public function add(AttributeGroup $group);
    public function count(): int;
    public function all(): array;
}
