<?php

namespace spec\Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Model\Category;
use PhpSpec\ObjectBehavior;

class CategoryTreeGeneratorSpec extends ObjectBehavior
{
    function it_generates_a_category_tree_of_one_hundred_categories_on_three_levels ()
    {
        $tree = $this->generate(100, 3);
        $tree->shouldBeAnInstanceOf(Category::class);
        $childrenLevel1 = $tree->getChildren();
        $childrenLevel1->shouldHaveCount(4);
        $childrenLevel2 = $childrenLevel1[0]->getChildren();
        $childrenLevel2->shouldHaveCount(4);
        $childrenLevel3 = $childrenLevel2[0]->getChildren();
        $childrenLevel3->shouldHaveCount(4);
        $childrenLevel4 = $childrenLevel3[0]->getChildren();
        $childrenLevel4->shouldHaveCount(0);
    }

    function it_generates_a_category_tree_of_one_hundred_categories_on_one_level ()
    {
        $tree = $this->generate(100, 1);
        $tree->shouldBeAnInstanceOf(Category::class);
        $childrenLevel1 = $tree->getChildren();
        $childrenLevel1->shouldHaveCount(99);
        $childrenLevel2 = $childrenLevel1[0]->getChildren();
        $childrenLevel2->shouldHaveCount(0);
    }
}
