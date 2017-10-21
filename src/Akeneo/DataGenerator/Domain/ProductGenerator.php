<?php

namespace Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Exception\NoChildrenCategoryDefinedException;
use Akeneo\DataGenerator\Domain\Exception\NoFamilyDefinedException;
use Faker\Factory;
use Faker\Generator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\Product\Categories;
use Akeneo\DataGenerator\Domain\Model\Product\Values;
use Akeneo\DataGenerator\Domain\ProductGenerator\ProductValueGeneratorRegistry;

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
        if ($this->familyRepository->count() === 0) {
            throw new NoFamilyDefinedException("At least one family should exist");
        }
        $families = $this->familyRepository->all();

        return $families[rand(0, count($families) -1)];
    }

    private function getRandomCategories(): Categories
    {
        if ($this->categoryRepository->countChildren() === 0) {
            throw new NoChildrenCategoryDefinedException("At least one children category should exist");
        }

        $categories = $this->categoryRepository->allChildren();
        $randomCodes = [];
        $randomCategories = new Categories();
        for ($ind = 0; $ind < 4; $ind++) {
            /** @var Category $category */
            $category = $categories[rand(0, count($categories) - 1)];
            if (!in_array($category->code(), $randomCodes)) {
                $randomCodes[] = $category->code();
                $randomCategories->add($category);
            }
        }

        return $randomCategories;
    }

    private function getRandomValues(Family $family): Values
    {
        $attributes = $family->attributes();
        $values = new Values();
        /** @var Attribute $attribute */
        foreach ($attributes->all() as $attribute) {
            $this->generateValues($values, $attribute);
        }

        return $values;
    }

    private function getRandomValuesExceptImages(Family $family): Values
    {
        $attributes = $family->attributes();
        $values = new Values();
        /** @var Attribute $attribute */
        foreach ($attributes->all() as $attribute) {
            if ($attribute->type() !== AttributeTypes::IMAGE) {
                $this->generateValues($values, $attribute);
            }
        }

        return $values;
    }

    private function generateValues(Values $values, Attribute $attribute)
    {
        if (!$this->valueGeneratorRegistry->support($attribute)) {
            return;
        }
        $generator = $this->valueGeneratorRegistry->get($attribute);

        if ($attribute->scopable() && $attribute->localizable()) {
            foreach ($this->channelRepository->all() as $channel) {
                foreach ($channel->locales() as $locale) {
                    $values->add($generator->generate($attribute, $channel->code(), $locale->code()));
                }
            }
        } elseif ($attribute->scopable()) {
            foreach ($this->channelRepository->all() as $channel) {
                $values->add($generator->generate($attribute, $channel->code(), null));
            }
        } elseif ($attribute->localizable()) {
            foreach ($this->localeRepository->allEnabled() as $locale) {
                $values->add($generator->generate($attribute, null, $locale->code()));
            }
        } else {
            $values->add($generator->generate($attribute, null, null));
        }
    }
}
