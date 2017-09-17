<?php

namespace Nidup\Sandbox\Domain;

interface MeasureFamilyRepository
{
    public function get(string $code): MeasureFamily;
    public function add(MeasureFamily $item);
    public function count(): int;
    public function all(): array;
}
