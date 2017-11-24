<?php
declare(strict_types=1);

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
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    public function generate(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
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
            return $this->generateTextAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::TEXTAREA) {
            return $this->generateTextAreaAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::OPTION_SIMPLE_SELECT) {
            return $this->generateSimpleSelectAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::OPTION_MULTI_SELECT) {
            return $this->generateMultiSelectAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::IMAGE) {
            return $this->generateImageAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::DATE) {
            return $this->generateDateAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::BOOLEAN) {
            return $this->generateBooleanAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::NUMBER) {
            return $this->generateNumberAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::METRIC) {
            return $this->generateMetricAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::PRICE_COLLECTION) {
            return $this->generatePriceCollectionAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        } elseif ($type === AttributeTypes::FILE) {
            return $this->generateFileAttribute($isUseableInGrid, $isLocalizable, $isScopable);
        }
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateTextAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXT;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateTextAreaAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::TEXTAREA;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'wysiwyg_enabled' => true
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateSimpleSelectAttribute(
        bool $isUseableInGrid,
        bool $isLocalizable,
        bool $isScopable
    ): Attribute {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_SIMPLE_SELECT;
        $properties = new Properties(['useable_as_grid_filter' => $isUseableInGrid]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateMultiSelectAttribute(
        bool $isUseableInGrid,
        bool $isLocalizable,
        bool $isScopable
    ): Attribute {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::OPTION_MULTI_SELECT;
        $properties = new Properties(['useable_as_grid_filter' => $isUseableInGrid]);
        $options = $this->generateRandomOptions();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateImageAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::IMAGE;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'allowed_extensions' => ['jpg', 'jpeg', 'gif', 'png']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateFileAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::FILE;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'allowed_extensions' => ['pdf']
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateDateAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::DATE;
        $properties = new Properties(['useable_as_grid_filter' => $isUseableInGrid,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateNumberAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::NUMBER;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'decimals_allowed' => true,
                'negative_allowed' => false
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateBooleanAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::BOOLEAN;
        $properties = new Properties(['useable_as_grid_filter' => $isUseableInGrid,]);
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generatePriceCollectionAttribute(
        bool $isUseableInGrid,
        bool $isLocalizable,
        bool $isScopable
    ): Attribute {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::PRICE_COLLECTION;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'decimals_allowed' => true,
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @param bool $isUseableInGrid
     * @param bool $isLocalizable
     * @param bool $isScopable
     *
     * @return Attribute
     */
    private function generateMetricAttribute(bool $isUseableInGrid, bool $isLocalizable, bool $isScopable): Attribute
    {
        $code = $this->generator->unique()->ean13;
        $type = AttributeTypes::METRIC;
        $properties = new Properties(
            [
                'useable_as_grid_filter' => $isUseableInGrid,
                'metric_family' => 'Weight',
                'default_metric_unit' => 'KILOGRAM',
                'decimals_allowed' => true,
                'negative_allowed' => false
            ]
        );
        $options = new Options();
        $group = $this->generateRandomGroup();

        return new Attribute($code, $type, $isLocalizable, $isScopable, $properties, $options, $group);
    }

    /**
     * @return Options
     */
    private function generateRandomOptions(): Options
    {
        $randomOptions = [];
        for ($ind = 0; $ind < 20; $ind++) {
            $randomOptions[]= new Attribute\Option($this->generator->unique()->ean13);
        }

        return new Options($randomOptions);
    }

    /**
     * @return AttributeGroup
     */
    private function generateRandomGroup(): AttributeGroup
    {
        if ($this->groupRepository->count() === 0) {
            throw new NoAttributeGroupDefinedException("At least one attribute group should exist");
        }
        $groups = $this->groupRepository->all();
        return $groups[rand(0, count($groups) -1)];
    }
}
