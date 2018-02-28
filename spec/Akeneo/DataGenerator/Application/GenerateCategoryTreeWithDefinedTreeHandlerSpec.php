<?php

namespace spec\Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Application\GenerateCategoryTree;
use Akeneo\DataGenerator\Application\GenerateCategoryTreeWithDefinedTree;
use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use PhpSpec\ObjectBehavior;

class GenerateCategoryTreeWithDefinedTreeHandlerSpec extends ObjectBehavior
{
    function let(CategoryTreeGenerator $generator, CategoryRepository $repository)
    {
        $this->beConstructedWith($generator, $repository);
    }

    function it_generates_a_tree_with_two_children_and_one_level(
        $generator,
        $repository,
        Category $tree,
        Category $child1,
        Category $child2
    ) {
        $command = new GenerateCategoryTreeWithDefinedTree('tree', 2, 1);

        $generator->generateWithDefinedTree('tree', 2, 1)->willReturn($tree);
        $repository->upsert($tree)->shouldBeCalled();

        $tree->children()->willReturn([$child1, $child2]);
        $repository->upsertMany([$child1, $child2])->shouldBeCalled();

        $child1->children()->willReturn([]);
        $child2->children()->willReturn([]);

        $this->handle($command);
    }
}
