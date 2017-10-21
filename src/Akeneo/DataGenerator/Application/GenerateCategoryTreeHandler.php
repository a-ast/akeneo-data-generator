<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;

class GenerateCategoryTreeHandler
{
    private $generator;
    private $repository;

    public function __construct(CategoryTreeGenerator $generator, CategoryRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateCategoryTree $command)
    {
        $tree = $this->generator->generate($command->getChildren(), $command->getLevel());
        $this->addCategoryAndChildren($tree);
    }

    private function addCategoryAndChildren(Category $category)
    {
        $this->repository->add($category);
        foreach ($category->children() as $child) {
            $this->addCategoryAndChildren($child);
        }
    }
}
