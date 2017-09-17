<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;

class InMemoryFamilyRepository implements FamilyRepository
{
    private $families = [];

    public function __construct()
    {
        $this->families = [];
    }

    public function get(string $code): Family
    {
        if (!isset($this->families[$code])) {
            throw new \Exception(sprintf("Family %s does not exists", $code));
        }

        return $this->families[$code];
    }

    public function add(Family $attribute)
    {
        $this->families[$attribute->getCode()] = $attribute;
    }

    public function count(): int
    {
        return count($this->families);
    }

    public function all(): array
    {
        return array_values($this->families);
    }
}
