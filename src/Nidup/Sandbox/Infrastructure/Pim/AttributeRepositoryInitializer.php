<?php

namespace Nidup\Sandbox\Infrastructure\Pim;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeProperties;
use Nidup\Sandbox\Domain\AttributeRepository;

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
            $repository->add(
                new Attribute(
                    $attributeData['code'],
                    $attributeData['type'],
                    $attributeData['localizable'],
                    $attributeData['scopable'],
                    $properties
                )
            );
        }
    }

    private function buildProperties(array $attributeData): AttributeProperties
    {
        $properties = [];
        $type = $attributeData['type'];
        if ($type === 'pim_catalog_text' || $type === 'pim_catalog_textarea') {
            $properties['max_characters'] = $attributeData['max_characters'];
        }

        return new AttributeProperties($properties);
    }
}
