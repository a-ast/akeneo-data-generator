<?php

namespace Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeOption;
use Akeneo\ApiSandbox\Domain\Model\AttributeOptions;
use Akeneo\ApiSandbox\Domain\Model\AttributeProperties;
use Akeneo\ApiSandbox\Domain\Model\AttributeRepository;
use Akeneo\ApiSandbox\Domain\Model\AttributeTypes;

class AttributeRepositoryInitializer
{
    private $client;
    private $groupRepository;

    public function __construct(AkeneoPimClientInterface $client, AttributeGroupRepository $groupRepository)
    {
        $this->client = $client;
        $this->groupRepository = $groupRepository;
    }

    public function initialize(AttributeRepository $repository)
    {
        $cursor = $this->client->getAttributeApi()->all();
        foreach ($cursor as $attributeData) {
            $properties = $this->buildProperties($attributeData);
            $options = $this->buildAttributeOptions($attributeData);
            $group = $this->groupRepository->get($attributeData['group']);
            $repository->add(
                new Attribute(
                    $attributeData['code'],
                    $attributeData['type'],
                    $attributeData['localizable'],
                    $attributeData['scopable'],
                    $properties,
                    $options,
                    $group
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
