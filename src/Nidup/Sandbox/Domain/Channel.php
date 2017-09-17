<?php

namespace Nidup\Sandbox\Domain;

class Channel
{
    /** @var string */
    private $code;
    /** @var Locale[] */
    private $locales;

    public function __construct(string $code, array $locales)
    {
        $this->code = $code;
        $this->locales = $locales;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }
}
