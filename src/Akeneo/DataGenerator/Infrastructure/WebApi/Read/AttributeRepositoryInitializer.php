<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Read;

use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Attribute\Option;
use Akeneo\DataGenerator\Domain\Model\Attribute\Options;
use Akeneo\DataGenerator\Domain\Model\Attribute\Properties;
use Akeneo\DataGenerator\Domain\Model\AttributeRepository;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;

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
        $cursor = $this->client->getAttributeApi()->all(100);
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

    private function buildProperties(array $attributeData): Properties
    {
        $properties = [];
        $type = $attributeData['type'];
        if ($type === AttributeTypes::TEXT || $type === AttributeTypes::TEXTAREA) {
            $properties['max_characters'] = $attributeData['max_characters'];
        } elseif ($type === AttributeTypes::METRIC) {
            $properties['metric_family'] = $attributeData['metric_family'];
            $properties['default_metric_unit'] = $attributeData['default_metric_unit'];
        }

        return new Properties($properties);
    }

    private function buildAttributeOptions(array $attributeData): Options
    {
        $type = $attributeData['type'];
        $attributeCode = $attributeData['code'];
        if ($type === AttributeTypes::OPTION_SIMPLE_SELECT || $type === AttributeTypes::OPTION_MULTI_SELECT) {
            $cursor = $this->client->getAttributeOptionApi()->all($attributeCode);
            $optionItems = [];
            foreach ($cursor as $optionData) {
                $optionItems[] = new Option($optionData['code']);
            }
            $options = new Options($optionItems);
        } else {
            $options = new Options();
        }

        return $options;
    }
}
