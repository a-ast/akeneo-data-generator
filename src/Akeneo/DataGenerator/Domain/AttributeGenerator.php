<?php

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Exception\NoAttributeGroupDefinedException;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeGroupRepository;
use Akeneo\DataGenerator\Domain\Model\Attribute\Options;
use Akeneo\DataGenerator\Domain\Model\Attribute\Properties;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
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
     * @param bool $useableInGrid
     *
     * @return Attribute
     */
    public function generate(bool $useableInGrid): Attribute
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
            AttributeTypes::FILE,
            AttributeTypes::IMAGE
        ];
        $type = $types[rand(0, count($types) - 1)];
        if ($type === AttributeTypes::TEXT) {
            return $this->generateTextAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::TEXTAREA) {
            return $this->generateTextAreaAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::OPTION_SIMPLE_SELECT) {
            return $this->generateSimpleSelectAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::OPTION_MULTI_SELECT) {
            return $this->generateMultiSelectAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::IMAGE) {
            return $this->generateImageAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::DATE) {
            return $this->generateDateAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::BOOLEAN) {
            return $this->generateBooleanAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::NUMBER) {
            return $this->generateNumberAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::METRIC) {
            return $this->generateMetricAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::PRICE_COLLECTION) {
            return $this->generatePriceCollectionAttribute($useableInGrid);
        } elseif ($type === AttributeTypes::FILE) {
            return $this->generateFileAttribute($useableInGrid);
        }
    }

    private function generateTextAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXT;
        $localizable = (rand(0, 1) == 1);
        $scopable = (rand(0, 1) == 1);
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateTextAreaAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXTAREA;
        $localizable = (rand(0, 1) == 1);
        $scopable = (rand(0, 1) == 1);
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
                'wysiwyg_enabled' => true
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateSimpleSelectAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_SIMPLE_SELECT;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => $useableInGrid]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateMultiSelectAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_MULTI_SELECT;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => $useableInGrid]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateImageAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::IMAGE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
                'allowed_extensions' => ['jpg', 'jpeg', 'gif', 'png']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateFileAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::FILE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
                'allowed_extensions' => ['pdf']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateDateAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::DATE;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => $useableInGrid,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateNumberAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::NUMBER;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
                'decimals_allowed' => true,
                'negative_allowed' => false
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateBooleanAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::BOOLEAN;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(['useable_as_grid_filter' => $useableInGrid,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generatePriceCollectionAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::PRICE_COLLECTION;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
                'decimals_allowed' => true,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $localizable, $scopable, $properties, $options, $group);
    }

    private function generateMetricAttribute(bool $useableInGrid): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::METRIC;
        $localizable = false;
        $scopable = false;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $useableInGrid,
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
