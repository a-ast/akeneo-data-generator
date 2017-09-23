<?php

namespace Nidup\Sandbox\Application;

class GenerateProduct
{
    /** @var bool */
    private $withImages;

    /** @param bool $withImages */
    public function __construct(bool $withImages)
    {
       $this->withImages = $withImages;
    }

    public function withImages(): bool
    {
        return $this->withImages;
    }
}
