<?php

namespace Akeneo\ApiSandbox\Application;

class GenerateAttribute
{
    private $useableInGrid;

    public function __construct(bool $useableInGrid)
    {
        $this->useableInGrid = $useableInGrid;
    }

    /**
     * @return bool
     */
    public function isUseableInGrid(): bool
    {
        return $this->useableInGrid;
    }
}
