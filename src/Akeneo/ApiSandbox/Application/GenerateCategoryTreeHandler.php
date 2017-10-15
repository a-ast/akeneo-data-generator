<?php

namespace Akeneo\ApiSandbox\Application;

use Akeneo\ApiSandbox\Domain\CategoryTreeGenerator;
use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Domain\Model\CategoryRepository;

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
        foreach ($category->getChildren() as $child) {
            $this->addCategoryAndChildren($child);
        }
    }
}
