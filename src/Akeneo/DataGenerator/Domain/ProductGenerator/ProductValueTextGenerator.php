<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

class ProductValueTextGenerator implements ProductValueGenerator
{
    /** @var Generator[] */
    private $generators = [];

    /**
     * @param string $locale
     * @return Generator
     */
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

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $maxNbChars = $attribute->properties()->getProperty('max_characters');
        $maxNbChars = $maxNbChars !== null ? $maxNbChars : 100;

        return new Value($attribute, $this->generator($localeCode)->realText($maxNbChars), $localeCode, $channelCode);
    }
}
