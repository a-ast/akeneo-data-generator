<?php

namespace Akeneo\Sandbox\Domain;

use Faker\Factory;
use Faker\Generator;
use Akeneo\Sandbox\Domain\Model\Attribute;
use Akeneo\Sandbox\Domain\Model\AttributeTypes;
use Akeneo\Sandbox\Domain\Model\Category;
use Akeneo\Sandbox\Domain\Model\CategoryRepository;
use Akeneo\Sandbox\Domain\Model\ChannelRepository;
use Akeneo\Sandbox\Domain\Model\CurrencyRepository;
use Akeneo\Sandbox\Domain\Model\Family;
use Akeneo\Sandbox\Domain\Model\FamilyRepository;
use Akeneo\Sandbox\Domain\Model\LocaleRepository;
use Akeneo\Sandbox\Domain\Model\Product;
use Akeneo\Sandbox\Domain\Model\ProductCategories;
use Akeneo\Sandbox\Domain\Model\ProductValues;
use Akeneo\Sandbox\Domain\ProductGenerator\ProductValueGeneratorRegistry;

class ProductGenerator
{
    /** @var ChannelRepository */
    private $channelRepository;
    /** @var LocaleRepository */
    private $localeRepository;
    /** @var CurrencyRepository */
    private $currencyRepository;
    /** @var FamilyRepository */
    private $familyRepository;
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var  Generator */
    private $identifierGenerator;
    /** @var ProductValueGeneratorRegistry */
    private $valueGeneratorRegistry;

    public function __construct(
        ChannelRepository $channelRepository,
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        FamilyRepository $familyRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
        $this->currencyRepository = $currencyRepository;
        $this->familyRepository = $familyRepository;
        $this->categoryRepository = $categoryRepository;
        $this->identifierGenerator = Factory::create();
        $this->valueGeneratorRegistry = new ProductValueGeneratorRegistry($currencyRepository);
    }

    public function generateWithImages(): Product
    {
        $identifier = $this->identifierGenerator->ean13();
        $family = $this->getRandomFamily();
        $values = $this->getRandomValues($family);
        $categories = $this->getRandomCategories();

        return new Product($identifier, $family, $values, $categories);
    }

    public function generateWithoutImages(): Product
    {
        $identifier = $this->identifierGenerator->ean13();
        $family = $this->getRandomFamily();
        $values = $this->getRandomValuesExceptImages($family);
        $categories = $this->getRandomCategories();

        return new Product($identifier, $family, $values, $categories);
    }

    private function getRandomFamily(): Family
    {
        $families = $this->familyRepository->all();

        return $families[rand(0, count($families) -1)];
    }

    private function getRandomCategories(): ProductCategories
    {
        $categories = $this->categoryRepository->all();
        $randomCodes = [];
        $randomCategories = new ProductCategories();
        for ($ind = 0; $ind < 4; $ind++) {
            /** @var Category $category */
            $category = $categories[rand(0, count($categories) - 1)];
            if (!in_array($category->getCode(), $randomCodes) && !$category->isRoot()) {
                $randomCodes[] = $category->getCode();
                $randomCategories->add($category);
            }
        }

        return $randomCategories;
    }

    private function getRandomValues(Family $family): ProductValues
    {
        $attributes = $family->getAttributes();
        $values = new ProductValues();
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            $this->generateValues($values, $attribute);
        }

        return $values;
    }

    private function getRandomValuesExceptImages(Family $family): ProductValues
    {
        $attributes = $family->getAttributes();
        $values = new ProductValues();
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getType() !== AttributeTypes::IMAGE) {
                $this->generateValues($values, $attribute);
            }
        }

        return $values;
    }

    private function generateValues(ProductValues $values, Attribute $attribute)
    {
        if (!$this->valueGeneratorRegistry->support($attribute)) {
            return;
        }
        $generator = $this->valueGeneratorRegistry->get($attribute);

        if ($attribute->isScopable() && $attribute->isLocalizable()) {
            foreach ($this->channelRepository->all() as $channel) {
                foreach ($channel->getLocales() as $locale) {
                    $values->add($generator->generate($attribute, $channel->getCode(), $locale->getCode()));
                }
            }
        } elseif ($attribute->isScopable()) {
            foreach ($this->channelRepository->all() as $channel) {
                $values->add($generator->generate($attribute, $channel->getCode(), null));
            }
        } elseif ($attribute->isLocalizable()) {
            foreach ($this->localeRepository->all() as $locale) {
                $values->add($generator->generate($attribute, null, $locale->getCode()));
            }
        } else {
            $values->add($generator->generate($attribute, null, null));
        }
    }
}
