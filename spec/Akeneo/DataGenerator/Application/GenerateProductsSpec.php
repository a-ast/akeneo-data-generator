<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\Exception\TooManyEntitiesException;
use PhpSpec\ObjectBehavior;

class GenerateProductsSpec extends ObjectBehavior
{
    function it_throws_an_exception_when_asking_more_than_100_products ()
    {
        $this->shouldThrow(
            new TooManyEntitiesException("Can't generate 101 products at a time, the maximum allowed is 100")
        )->during(
            '__construct',
            [101, false]
        );
    }
}
