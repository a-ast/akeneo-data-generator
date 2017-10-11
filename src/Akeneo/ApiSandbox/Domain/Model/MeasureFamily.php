<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class MeasureFamily
{
    private $code;
    private $standard;
    private $units;

    public function __construct(string $code, string $standard, array $units)
    {
        $this->code = $code;
        $this->standard = $standard;
        $this->units = $units;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getStandard(): string
    {
        return $this->standard;
    }

    /**
     * @return array
     */
    public function getUnits(): array
    {
        return $this->units;
    }
}
