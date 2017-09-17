<?php

namespace Nidup\Sandbox\Infrastructure\Pim;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Category;
use Nidup\Sandbox\Domain\CategoryRepository;

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
            if ($itemData['parent'] !== null)  {
                $parent = $repository->get($itemData['parent']);
            }
            $repository->add(new Category($itemData['code'], $parent));
        }
    }
}
