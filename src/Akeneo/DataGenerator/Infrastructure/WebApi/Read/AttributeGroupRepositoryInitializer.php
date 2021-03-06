<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;

class AttributeGroupRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(AttributeGroupRepository $repository)
    {
        $cursor = $this->client->getAttributeGroupApi()->all(100);
        foreach ($cursor as $itemData) {
            $repository->add(new AttributeGroup($itemData['code']));
        }
    }
}
