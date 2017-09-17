<?php

namespace Nidup\Sandbox\Application;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\ChannelRepository;
use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;
use Nidup\Sandbox\Domain\LocaleRepository;
use Nidup\Sandbox\Domain\Product;
use Nidup\Sandbox\Domain\ProductValue;
use Nidup\Sandbox\Domain\ProductValues;

class ProductGenerator
{
    /** @var Generator[] */
    private $generators;
    /** @var ChannelRepository */
    private $channelRepository;
    /** @var LocaleRepository */
    private $localeRepository;
    /** @var FamilyRepository */
    private $familyRepository;

    public function __construct(
        ChannelRepository $channelRepository,
        LocaleRepository $localeRepository,
        FamilyRepository $familyRepository
    ) {
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
        $this->familyRepository = $familyRepository;
    }

    public function generate(): Product
    {
        $identifier = $this->generator()->ean13();
        $family = $this->getRandomFamily();
        $values = $this->getRandomValues($family);

        return new Product($identifier, $family, $values, []);
    }

    private function generator($locale = null)
    {
        if ($locale === null) {
            $locale = 'en_US';
        }
        if (!isset($this->generators[$locale])) {
            $this->generators[$locale] = Factory::create($locale);
        }

        return $this->generators[$locale];
    }

    private function getRandomFamily(): Family
    {
        $families = $this->familyRepository->all();

        return $families[rand(0, count($families) -1 )];
    }

    private function getRandomValues(Family $family): ProductValues
    {
        $attributes = $family->getAttributes();
        $values = new ProductValues();
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getType() === 'pim_catalog_text' || $attribute->getType() === 'pim_catalog_textarea') {
                $this->generateTextValues($values, $attribute);
            }
        }

        return $values;
    }

    private function generateTextValues(ProductValues $values, Attribute $attribute)
    {
        if ($attribute->isScopable() && $attribute->isLocalizable()) {
            foreach ($this->channelRepository->all() as $channel) {
                foreach ($channel->getLocales() as $locale) {
                    $values->addValue($this->generateTextValue($attribute, $channel->getCode(), $locale->getCode()));
                }
            }
        } else if ($attribute->isScopable()) {
            foreach ($this->channelRepository->all() as $channel) {
                $values->addValue($this->generateTextValue($attribute, $channel->getCode(), null));
            }
        } else if ($attribute->isLocalizable()) {
            foreach ($this->localeRepository->all() as $locale) {
                $values->addValue($this->generateTextValue($attribute, null, $locale->getCode()));
            }
        } else {
            $values->addValue($this->generateTextValue($attribute, null, null));
        }
    }

    private function generateTextValue(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $maxNbChars = $attribute->getProperties()->getProperty('max_characters');
        $maxNbChars = $maxNbChars !== null ? $maxNbChars : 100;

        return new ProductValue($attribute, $this->generator($localeCode)->realText($maxNbChars), $localeCode, $channelCode);
    }
}
