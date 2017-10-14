<?php

namespace Akeneo\ApiSandbox\Domain;

use Akeneo\ApiSandbox\Domain\Exception\NoAttributeGroupDefinedException;
use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroup;
use Akeneo\ApiSandbox\Domain\Model\AttributeGroupRepository;
use Akeneo\ApiSandbox\Domain\Model\Attribute\Options;
use Akeneo\ApiSandbox\Domain\Model\Attribute\Properties;
use Akeneo\ApiSandbox\Domain\Model\AttributeTypes;
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
        $types = [
            AttributeTypes::BOOLEAN,
            AttributeTypes::DATE,
            AttributeTypes::TEXT,
            AttributeTypes::TEXTAREA,
            AttributeTypes::OPTION_SIMPLE_SELECT,
            AttributeTypes::OPTION_MULTI_SELECT,
            AttributeTypes::NUMBER,
            AttributeTypes::METRIC,
            AttributeTypes::PRICE_COLLECTION,
            AttributeTypes::FILE
        ];
        $type = $types[rand(0, count($types) - 1)];
        if ($type === AttributeTypes::TEXT) {
            return $this->generateTextAttribute();
        } elseif ($type === AttributeTypes::TEXTAREA) {
            return $this->generateTextAreaAttribute();
        } elseif ($type === AttributeTypes::OPTION_SIMPLE_SELECT) {
            return $this->generateSimpleSelectAttribute();
        } elseif ($type === AttributeTypes::OPTION_MULTI_SELECT) {
            return $this->generateMultiSelectAttribute();
        } elseif ($type === AttributeTypes::IMAGE) {
            return $this->generateImageAttribute();
        } elseif ($type === AttributeTypes::DATE) {
            return $this->generateDateAttribute();
        } elseif ($type === AttributeTypes::BOOLEAN) {
            return $this->generateBooleanAttribute();
        } elseif ($type === AttributeTypes::NUMBER) {
            return $this->generateNumberAttribute();
        } elseif ($type === AttributeTypes::METRIC) {
            return $this->generateMetricAttribute();
        } elseif ($type === AttributeTypes::PRICE_COLLECTION) {
            return $this->generatePriceCollectionAttribute();
        } elseif ($type === AttributeTypes::FILE) {
            return $this->generateFileAttribute();
        }
    }

    private function generateTextAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXT;
        $localizable = (rand(0, 1) == 1);
        $scopable = (rand(0, 1) == 1);
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateTextAreaAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXTAREA;
        $localizable = (rand(0, 1) == 1);
        $scopable = (rand(0, 1) == 1);
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'wysiwyg_enabled' => true
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateSimpleSelectAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_SIMPLE_SELECT;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => true]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateMultiSelectAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_MULTI_SELECT;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => true]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateImageAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::IMAGE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'allowed_extensions' => ['jpg', 'jpeg', 'gif', 'png']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateFileAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::FILE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'allowed_extensions' => ['pdf']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateDateAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::DATE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => true,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateNumberAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::NUMBER;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'decimals_allowed' => true,
                'negative_allowed' => false
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateBooleanAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::BOOLEAN;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => true,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generatePriceCollectionAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::PRICE_COLLECTION;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'decimals_allowed' => true,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateMetricAttribute(): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::METRIC;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => true,
                'metric_family' => 'Weight',
                'default_metric_unit' => 'KILOGRAM',
                'decimals_allowed' => true,
                'negative_allowed' => false
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateRandomOptions(): Options
    {
        $randomOptions = [];
        for ($ind = 0; $ind < 20; $ind++) {
            $randomOptions[]= new Attribute\Option($this->generator->unique()->ean13);
        }

        return new Options($randomOptions);
    }

    private function generateRandomGroup(): AttributeGroup
    {
        if ($this->groupRepository->count() === 0) {
            throw new NoAttributeGroupDefinedException("At least one attribute group should exist");
        }
        $groups = $this->groupRepository->all();
        return $groups[rand(0, count($groups) -1)];
    }
}
