<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

class ProductValueDateGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $data = $this->generator->date('Y-m-d');

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
