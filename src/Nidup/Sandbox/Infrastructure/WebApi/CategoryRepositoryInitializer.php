<?php

namespace Nidup\Sandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Model\Category;
use Nidup\Sandbox\Domain\Model\CategoryRepository;

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
            $repository->add(new Category($itemData['code'], $parent));
        }
    }
}
