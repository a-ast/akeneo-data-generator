<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\AttributeGroup;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class AttributeGroupRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(AttributeGroupRepository $repository)
    {
        $cursor = $this->client->getAttributeGroupApi()->all();
        foreach ($cursor as $itemData) {
            $repository->add(new AttributeGroup($itemData['code']));
        }
    }
}
