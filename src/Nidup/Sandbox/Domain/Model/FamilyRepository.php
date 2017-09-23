<?php

namespace Nidup\Sandbox\Domain\Model;

interface FamilyRepository
{
    public function get(string $code): Family;
    public function add(Family $family);
    public function count(): int;
    public function all(): array;
}
