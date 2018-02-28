<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;

class WebApiCategoryRepository implements CategoryRepository
{
    private $client;

    private const BATCH_SIZE = 100;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $code): Category
    {
        throw new \LogicException('not implemented yet');
    }

    public function add(Category $category)
    {
        $categoryData = [
            'parent' => $category->isRoot() ?  null : $category->parent()->code()
        ];
        $this->client->getCategoryApi()->create($category->code(), $categoryData);
    }

    public function upsert(Category $category)
    {
        $categoryData = [
            'parent' => $category->isRoot() ?  null : $category->parent()->code()
        ];
        $this->client->getCategoryApi()->upsert($category->code(), $categoryData);
    }

    public function upsertMany(array $categories): void
    {
        if (empty($categories)) {
            return;
        }

        $categoriesData = [];
        foreach ($categories as $category) {
            $categoriesData[] = [
                'code' => $category->code(),
                'parent' => $category->isRoot() ? null : $category->parent()->code(),
            ];
        }

        $chunkedCategoriesData = array_chunk($categoriesData, self::BATCH_SIZE);
        foreach ($chunkedCategoriesData as $chunk) {
            $this->client->getCategoryApi()->upsertList($chunk);
        }

    }

    public function count(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function all(): array
    {
        throw new \LogicException('not implemented yet');
    }

    public function countChildren(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function allChildren(): array
    {
        throw new \LogicException('not implemented yet');
    }

    public function countTrees(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function allTrees(): array
    {
        throw new \LogicException('not implemented yet');
    }
}
