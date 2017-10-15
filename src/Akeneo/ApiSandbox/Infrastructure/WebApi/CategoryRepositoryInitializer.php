<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\Category;
use Akeneo\ApiSandbox\Domain\Model\CategoryRepository;

class CategoryRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(CategoryRepository $repository)
    {
        $cursor = $this->client->getCategoryApi()->all();
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
