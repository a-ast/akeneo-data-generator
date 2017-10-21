<?php

namespace Akeneo\DataGenerator\Domain\Model;

class Locale
{
    /** @var string */
    private $code;
    /** @var string */
    private $enabled;

    public function __construct(string $code, bool $enabled)
    {
        $this->code = $code;
        $this->enabled = $enabled;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }
}
