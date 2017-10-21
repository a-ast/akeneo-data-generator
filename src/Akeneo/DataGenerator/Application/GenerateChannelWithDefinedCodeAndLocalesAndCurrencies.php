<?php

namespace Akeneo\DataGenerator\Application;

class GenerateChannelWithDefinedCodeAndLocalesAndCurrencies
{
    private $code;
    private $locales;
    private $currencies;

    public function __construct(string $code, array $locales, array $currencies)
    {
        $this->code = $code;
        $this->locales = $locales;
        $this->currencies = $currencies;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function locales(): array
    {
        return $this->locales;
    }

    /**
     * @return array
     */
    public function currencies(): array
    {
        return $this->currencies;
    }
}
