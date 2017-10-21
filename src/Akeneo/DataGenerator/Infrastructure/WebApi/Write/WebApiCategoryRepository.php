<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class WebApiCategoryRepository implements CategoryRepository
{
    private $client;

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
            'parent' => $category->getParent() !== null ? $category->getParent()->getCode() : null,
        ];
        $this->client->getCategoryApi()->create($category->getCode(), $categoryData);
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
