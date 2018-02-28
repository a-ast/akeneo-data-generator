<?php

namespace Akeneo\DataGenerator\Application;

use Akeneo\DataGenerator\Domain\CategoryTreeGenerator;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;

class GenerateCategoryTreeWithDefinedTreeHandler
{
    private $generator;
    private $repository;

    public function __construct(CategoryTreeGenerator $generator, CategoryRepository $repository)
    {
        $this->generator = $generator;
        $this->repository = $repository;
    }

    public function handle(GenerateCategoryTreeWithDefinedTree $command)
    {
        $tree = $this->generator->generateWithDefinedTree(
            $command->getTree(),
            $command->getChildren(),
            $command->getLevel()
        );
        $this->createTree($tree);
    }

    private function createTree(Category $tree)
    {
        $this->repository->upsert($tree);
        $this->addChildren($tree->children());
    }

    private function addChildren(array $categories)
    {
        $this->repository->upsertMany($categories);

        foreach ($categories as $child) {
            if (!empty($child->children())) {
                $this->addChildren($child->children());
            }
        }
    }
}
