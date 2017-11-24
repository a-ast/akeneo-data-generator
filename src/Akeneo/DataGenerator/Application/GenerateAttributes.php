<?php
declare(strict_types=1);

namespace Akeneo\DataGenerator\Application;

class GenerateAttributes
{
    /** @var int */
    private $count;

    /** @var int */
    private $percentageOfUseableInGrid;

    /** @var int */
    private $percentageOfLocalizable;

    /** @var int */
    private $percentageOfScopable;

    /** @var int */
    private $percentageOfLocalizableAndScopable;

    /**
     * @param int $count
     * @param int $percentageOfUseableInGrid
     * @param int $percentageOfLocalizable
     * @param int $percentageOfScopable
     * @param int $percentageOfLocalizableAndScopable
     */
    public function __construct(
        int $count,
        int $percentageOfUseableInGrid,
        int $percentageOfLocalizable,
        int $percentageOfScopable,
        int $percentageOfLocalizableAndScopable
    ) {
        $this->count = $count;
        $this->percentageOfUseableInGrid = $percentageOfUseableInGrid;
        $this->percentageOfLocalizable = $percentageOfLocalizable;
        $this->percentageOfScopable = $percentageOfScopable;
        $this->percentageOfLocalizableAndScopable = $percentageOfLocalizableAndScopable;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function percentageOfUseableInGrid(): int
    {
        return $this->percentageOfUseableInGrid;
    }

    /**
     * @return int
     */
    public function percentageOfLocalizable(): int
    {
        return $this->percentageOfLocalizable;
    }

    /**
     * @return int
     */
    public function percentageOfScopable(): int
    {
        return $this->percentageOfScopable;
    }

    /**
     * @return int
     */
    public function percentageOfLocalizableAndScopable(): int
    {
        return $this->percentageOfLocalizableAndScopable;
    }
}
