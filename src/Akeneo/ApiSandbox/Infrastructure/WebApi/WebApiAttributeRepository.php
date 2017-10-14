<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\Pim\AkeneoPimClientInterface;

class WebApiAttributeRepository implements AttributeRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $code): Attribute
    {
        throw new \LogicException('not implemented yet');
    }

    public function add(Attribute $attribute)
    {
        $attributeData = [
            'type' => $attribute->getType(),
            'localizable' => $attribute->isLocalizable(),
            'scopable' => $attribute->isScopable(),
            'group' => $attribute->getGroup()->getCode()
        ];
        foreach ($attribute->getProperties()->all() as $propertyCode => $propertyValue) {
            $attributeData[$propertyCode]= $propertyValue;
        }
        $this->client->getAttributeApi()->create($attribute->getCode(), $attributeData);

        foreach ($attribute->getOptions()->getCodes() as $optionCode) {
            $this->client->getAttributeOptionApi()->create($attribute->getCode(), $optionCode);
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
}
