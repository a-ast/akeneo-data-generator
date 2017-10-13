<?php

namespace Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Exception\NoAttributeGroupDefinedException;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroup;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use Akeneo\ApiSandbox\Domain\Model\AttributeOptions;
use Akeneo\ApiSandbox\Domain\Model\AttributeProperties;
use Faker\Factory;
use Faker\Generator;

class AttributeGenerator
{
    /** @var AttributeGroupRepository */
    private $groupRepository;

    /** @var Generator */
    private $generator;

    /**
     * @param AttributeGroupRepository $groupRepository
     */
    public function __construct(AttributeGroupRepository $groupRepository)
    {
        $this->generator = Factory::create();
        $this->groupRepository = $groupRepository;
    }

    /**
     * @return Attribute
     */
    public function generate(): Attribute
    {
        $code = $this->generator->unique()->word;
        $type = 'pim_catalog_text';
        $localizable = false;
        $scopable = false;
        $properties = new AttributeProperties([]);
        $attributeOptions = new AttributeOptions([]);
        $group = $this->generateRandomAttributeGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $attributeOptions, $group);
    }

    /**
     * @return AttributeGroup
     */
    private function generateRandomAttributeGroup(): AttributeGroup
    {
        if ($this->groupRepository->count() === 0) {
            throw new NoAttributeGroupDefinedException("At least one attribute group should exist");
        }
        $groups = $this->groupRepository->all();
        return $groups[rand(0, count($groups) -1)];
    }
}
