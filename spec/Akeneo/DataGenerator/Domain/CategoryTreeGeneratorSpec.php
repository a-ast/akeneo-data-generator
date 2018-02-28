<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Infrastructure\Cli\Catalog\CategoryTree;
use PhpSpec\ObjectBehavior;

class CategoryTreeGeneratorSpec extends ObjectBehavior
{
    function it_generates_a_category_tree_of_one_hundred_categories_on_three_levels ()
    {
        $tree = $this->generate(100, 3);
        $tree->shouldBeAnInstanceOf(Category::class);
        $tree->shouldHaveNumberOfCategoriesInTree(100);
        $tree->shouldHaveMaxDepth(4);

        $childrenLevel1 = $tree->children();
        $childrenLevel1->shouldHaveCount(19);

        $childrenLevel2 = $childrenLevel1[0]->children();
        $childrenLevel2->shouldHaveCount(4);
        $childrenLevel3 = $childrenLevel2[0]->children();
        $childrenLevel3->shouldHaveCount(4);
        $childrenLevel4 = $childrenLevel3[0]->children();
        $childrenLevel4->shouldHaveCount(0);
    }


    function it_generates_a_category_tree_of_one_hundred_categories_on_one_level ()
    {
        $tree = $this->generate(100, 1);
        $tree->shouldBeAnInstanceOf(Category::class);
        $tree->shouldHaveNumberOfCategoriesInTree(100);
        $tree->shouldHaveMaxDepth(2);

        $childrenLevel1 = $tree->children();
        $childrenLevel1->shouldHaveCount(99);
        $childrenLevel2 = $childrenLevel1[0]->children();
        $childrenLevel2->shouldHaveCount(0);

    }

    public function getMatchers(): array
    {
        return [
            'haveNumberOfCategoriesInTree' => function ($category, $count) {
                return $this->countCategories($category) === $count;
            },
            'haveMaxDepth' => function ($category, $depth) {
                return $this->maxDepth($category) === $depth;
            },
        ];
    }

    private function countCategories(Category $category)
    {
        $count = 1;
        foreach ($category->children() as $child) {
            $count += $this->countCategories($child) ;
        }

        return $count;
    }

    private function maxDepth(Category $category)
    {
        if (empty($category->children())) {
            return 1;
        }

        $childrenDepth = [];
        foreach ($category->children() as $child) {
            $childrenDepth[] = 1 + $this->maxDepth($child);
        }

        return max($childrenDepth);
    }
}
