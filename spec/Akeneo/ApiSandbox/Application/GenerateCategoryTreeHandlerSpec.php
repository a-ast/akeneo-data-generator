<?php

namespace spec\Akeneo\ApiSandbox\Application;

use Akeneo\ApiSandbox\Application\GenerateCategoryTree;
use Akeneo\ApiSandbox\Domain\CategoryTreeGenerator;
use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Domain\Model\CategoryRepository;
use PhpSpec\ObjectBehavior;

class GenerateCategoryTreeHandlerSpec extends ObjectBehavior
{
    function let(CategoryTreeGenerator $generator, CategoryRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_tree_with_two_children_and_one_level(
        $generator,
        $repository,
        GenerateCategoryTree $command,
        Category $tree,
        Category $child1,
        Category $child2
    ) {
        $command->getChildren()->willReturn(2);
        $command->getLevel()->willReturn(1);

        $generator->generate(2, 1)->willReturn($tree);
        $repository->add($tree)->shouldBeCalled();

        $tree->getChildren()->willReturn([$child1, $child2]);
        $repository->add($child1)->shouldBeCalled();
        $repository->add($child2)->shouldBeCalled();

        $child1->getChildren()->willReturn([]);
        $child2->getChildren()->willReturn([]);

        $this->handle($command);
    }
}
