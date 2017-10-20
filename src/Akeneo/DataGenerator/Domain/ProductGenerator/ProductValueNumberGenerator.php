<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

class ProductValueNumberGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $data = $this->generator->numberBetween(1, 100);

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
