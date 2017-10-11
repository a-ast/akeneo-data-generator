<?php

namespace Akeneo\ApiSandbox\Domain\Model;

class Channel
{
    /** @var string */
    private $code;
    /** @var Locale[] */
    private $locales;
    /** @var Currency[] */
    private $currencies;

    public function __construct(string $code, array $locales, array $currencies)
    {
        $this->code = $code;
        $this->locales = $locales;
        $this->currencies = $currencies;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }
}
