<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
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
            'type' => $attribute->type(),
            'localizable' => $attribute->localizable(),
            'scopable' => $attribute->scopable(),
            'group' => $attribute->group()->code()
        ];
        foreach ($attribute->properties()->all() as $propertyCode => $propertyValue) {
            $attributeData[$propertyCode]= $propertyValue;
        }
        $this->client->getAttributeApi()->create($attribute->code(), $attributeData);

        foreach ($attribute->options()->getCodes() as $optionCode) {
            $this->client->getAttributeOptionApi()->create($attribute->code(), $optionCode);
        }
    }

    public function addAll(array $attributes)
    {
        $attributesData = [];
        foreach ($attributes as $attribute) {
            $attributeData = [
                'code' => $attribute->code(),
                'type' => $attribute->type(),
                'localizable' => $attribute->localizable(),
                'scopable' => $attribute->scopable(),
                'group' => $attribute->group()->code()
            ];
            foreach ($attribute->properties()->all() as $propertyCode => $propertyValue) {
                $attributeData[$propertyCode]= $propertyValue;
            }
            $attributesData[] = $attributeData;
        }

        $this->client->getAttributeApi()->upsertList($attributesData);

        foreach ($attributes as $attribute) {
            foreach ($attribute->options()->getCodes() as $optionCode) {
                $this->client->getAttributeOptionApi()->create($attribute->code(), $optionCode);
            }
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
