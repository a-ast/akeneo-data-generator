<?php

namespace Nidup\Sandbox\Domain;

class Attribute
{
    private $code;
    private $type;
    private $localizable;
    private $scopable;

    public function __construct(string $code, string $type, bool $localizable, bool $scopable)
    {
        $this->code = $code;
        $this->type = $type;
        $this->localizable = $localizable;
        $this->scopable = $scopable;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isLocalizable(): bool
    {
        return $this->localizable;
    }

    /**
     * @return bool
     */
    public function isScopable(): bool
    {
        return $this->scopable;
    }
}
