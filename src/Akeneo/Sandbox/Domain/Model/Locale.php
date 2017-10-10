<?php

namespace Akeneo\Sandbox\Domain\Model;

class Locale
{
    /** @var string */
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
