<?php

namespace Akeneo\DataGenerator\Application;

class GenerateChannel
{
    /** @var int */
    private $localesNumber;
    /** @var int */
    private $currenciesNumber;

    /**
     * @param int $localesNumber
     * @param int $currenciesNumber
     *
     */
    public function __construct(int $localesNumber, int $currenciesNumber)
    {
        $this->localesNumber = $localesNumber;
        $this->currenciesNumber = $currenciesNumber;
    }

    /**
     * @return int
     */
    public function getLocalesNumber(): int
    {
        return $this->localesNumber;
    }

    /**
     * @return int
     */
    public function getCurrenciesNumber(): int
    {
        return $this->currenciesNumber;
    }
}
