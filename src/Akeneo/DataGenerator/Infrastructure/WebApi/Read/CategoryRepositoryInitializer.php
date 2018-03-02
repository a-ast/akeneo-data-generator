<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;

class CategoryRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(CategoryRepository $repository)
    {
        $cursor = $this->client->getCategoryApi()->all(100);
        foreach ($cursor as $itemData) {
            $parent = null;
            if ($itemData['parent'] !== null) {
                $parent = $repository->get($itemData['parent']);
            }
            $child = new Category($itemData['code'], $parent);
            $repository->add($child);
            if ($parent) {
                $parent->addChild($child);
            }
        }
    }
}
