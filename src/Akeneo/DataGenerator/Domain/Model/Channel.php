<?php

namespace Akeneo\DataGenerator\Domain\Model;

class Channel
{
    /** @var string */
    private $code;
    /** @var Locale[] */
    private $locales;
    /** @var Currency[] */
    private $currencies;
    /** @var Category[] */
    private $tree;

    public function __construct(string $code, array $locales, array $currencies, Category $tree)
    {
        $this->code = $code;
        $this->locales = $locales;
        $this->currencies = $currencies;
        $this->tree = $tree;
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

    public function tree(): Category
    {
        return $this->tree;
    }
}
