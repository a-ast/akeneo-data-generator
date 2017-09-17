<?php

namespace Nidup\Sandbox\Infrastructure\Pim;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeOption;
use Nidup\Sandbox\Domain\AttributeOptions;
use Nidup\Sandbox\Domain\AttributeProperties;
use Nidup\Sandbox\Domain\AttributeRepository;
use Nidup\Sandbox\Domain\AttributeTypes;

class AttributeRepositoryInitializer
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function initialize(AttributeRepository $repository)
    {
        $cursor = $this->client->getAttributeApi()->all();
        foreach ($cursor as $attributeData) {
            $properties = $this->buildProperties($attributeData);
            $options = $this->buildAttributeOptions($attributeData);
            $repository->add(
                new Attribute(
                    $attributeData['code'],
                    $attributeData['type'],
                    $attributeData['localizable'],
                    $attributeData['scopable'],
                    $properties,
                    $options
                )
            );
        }
    }

    private function buildProperties(array $attributeData): AttributeProperties
    {
        $properties = [];
        $type = $attributeData['type'];
        if ($type === AttributeTypes::TEXT || $type === AttributeTypes::TEXTAREA) {
            $properties['max_characters'] = $attributeData['max_characters'];
        }

        return new AttributeProperties($properties);
    }

    private function buildAttributeOptions(array $attributeData): AttributeOptions
    {
        $options = new AttributeOptions();
        $type = $attributeData['type'];
        $attributeCode = $attributeData['code'];
        if ($type === AttributeTypes::OPTION_SIMPLE_SELECT || $type === AttributeTypes::OPTION_MULTI_SELECT) {
            $cursor = $this->client->getAttributeOptionApi()->all($attributeCode);
            foreach ($cursor as $optionData) {
                $options->add(
                    new AttributeOption($optionData['code'])
                );
            }
        }

        return $options;
    }
}
