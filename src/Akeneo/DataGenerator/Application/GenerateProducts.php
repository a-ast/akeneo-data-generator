<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\Exception\TooManyEntitiesException;

class GenerateProducts
{
    /** @var int */
    private $count;

    /** @var bool */
    private $withImages;

    /**
     * @param int  $count
     * @param bool $withImages
     */
    public function __construct(int $count, bool $withImages)
    {
        $this->count = $count;
        $this->withImages = $withImages;
        $max = 100;
        if ($count > $max) {
            throw new TooManyEntitiesException(
                sprintf(
                    "Can't generate %d products at a time, the maximum allowed is %d",
                    $count,
                    $max
                )
            );
        }
    }

    public function count(): int
    {
        return $this->count;
    }

    public function withImages(): bool
    {
        return $this->withImages;
    }
}
