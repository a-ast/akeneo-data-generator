<?php

namespace Nidup\Sandbox\Application;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\Category;
use Nidup\Sandbox\Domain\CategoryRepository;
use Nidup\Sandbox\Domain\ChannelRepository;
use Nidup\Sandbox\Domain\CurrencyRepository;
use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;
use Nidup\Sandbox\Domain\LocaleRepository;
use Nidup\Sandbox\Domain\Product;
use Nidup\Sandbox\Domain\ProductCategories;
use Nidup\Sandbox\Domain\ProductValues;

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

    public function generate(): Product
    {
        $identifier = $this->identifierGenerator->ean13();
        $family = $this->getRandomFamily();
        $values = $this->getRandomValues($family);
        $categories = $this->getRandomCategories();

        return new Product($identifier, $family, $values, $categories);
    }

    private function getRandomFamily(): Family
    {
        $families = $this->familyRepository->all();

        return $families[rand(0, count($families) -1 )];
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
        } else if ($attribute->isScopable()) {
            foreach ($this->channelRepository->all() as $channel) {
                $values->add($generator->generate($attribute, $channel->getCode(), null));
            }
        } else if ($attribute->isLocalizable()) {
            foreach ($this->localeRepository->all() as $locale) {
                $values->add($generator->generate($attribute, null, $locale->getCode()));
            }
        } else {
            $values->add($generator->generate($attribute, null, null));
        }
    }
}
