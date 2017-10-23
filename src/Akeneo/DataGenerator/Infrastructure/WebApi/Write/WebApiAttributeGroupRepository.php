<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class WebApiAttributeGroupRepository implements AttributeGroupRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $code): AttributeGroup
    {
        throw new \LogicException('not implemented yet');
    }

    public function add(AttributeGroup $attribute)
    {
        $this->client->getAttributeGroupApi()->create($attribute->code(), []);
    }

    public function count(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function all(): array
    {
        throw new \LogicException('not implemented yet');
    }
}
