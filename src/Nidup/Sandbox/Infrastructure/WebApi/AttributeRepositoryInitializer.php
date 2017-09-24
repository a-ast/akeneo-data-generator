<?php

namespace Nidup\Sandbox\Infrastructure\WebApi;

use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\AttributeOption;
use Nidup\Sandbox\Domain\Model\AttributeOptions;
use Nidup\Sandbox\Domain\Model\AttributeProperties;
use Nidup\Sandbox\Domain\Model\AttributeRepository;
use Nidup\Sandbox\Domain\Model\AttributeTypes;

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
        } elseif ($type === AttributeTypes::METRIC) {
            $properties['metric_family'] = $attributeData['metric_family'];
            $properties['default_metric_unit'] = $attributeData['default_metric_unit'];
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
