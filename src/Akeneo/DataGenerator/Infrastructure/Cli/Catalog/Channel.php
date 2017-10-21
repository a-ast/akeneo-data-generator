<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class Channel
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

    public function code(): string
    {
        return $this->code;
    }

    public function locales(): array
    {
        return $this->locales;
    }

    public function currencies(): array
    {
        return $this->currencies;
    }
}
