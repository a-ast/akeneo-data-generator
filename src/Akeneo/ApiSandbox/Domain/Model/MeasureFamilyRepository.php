<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface MeasureFamilyRepository
{
    public function get(string $code): MeasureFamily;
    public function add(MeasureFamily $item);
    public function count(): int;
    public function all(): array;
}
