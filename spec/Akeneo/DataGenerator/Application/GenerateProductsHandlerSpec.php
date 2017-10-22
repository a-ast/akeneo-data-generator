<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateProducts;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\ProductRepository;
use Akeneo\DataGenerator\Domain\ProductGenerator;
use PhpSpec\ObjectBehavior;

class GenerateProductsHandlerSpec extends ObjectBehavior
{
    function let(ProductGenerator $generator, ProductRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_several_products_with_images(
        $generator,
        $repository,
        GenerateProducts $command,
        Product $product1,
        Product $product2
    ) {
        $command->count()->willReturn(2);
        $command->withImages()->willReturn(true);
        $generator->generateWithImages()->willReturn($product1, $product2);
        $repository->bulkAdd([$product1, $product2])->shouldBeCalled();

        $this->handle($command);
    }

    function it_generates_several_products_without_images(
        $generator,
        $repository,
        GenerateProducts $command,
        Product $product1,
        Product $product2
    ) {
        $command->count()->willReturn(2);
        $command->withImages()->willReturn(false);
        $generator->generateWithoutImages()->willReturn($product1, $product2);
        $repository->bulkAdd([$product1, $product2])->shouldBeCalled();

        $this->handle($command);
    }
}
