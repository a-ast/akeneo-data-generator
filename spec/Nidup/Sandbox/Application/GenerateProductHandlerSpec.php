<?php

namespace spec\Nidup\Sandbox\Application;

use Nidup\Sandbox\Application\GenerateProduct;
use Nidup\Sandbox\Domain\Model\Product;
use Nidup\Sandbox\Domain\Model\ProductRepository;
use Nidup\Sandbox\Domain\ProductGenerator;
use PhpSpec\ObjectBehavior;

class GenerateProductHandlerSpec extends ObjectBehavior
{
    function let(ProductGenerator $generator, ProductRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_product(
        $generator,
        $repository,
        GenerateProduct $command,
        Product $product
    )
    {
        $generator->generate()->willReturn($product);
        $repository->add($product)->shouldBeCalled();

        $this->handle($command);
    }
}
