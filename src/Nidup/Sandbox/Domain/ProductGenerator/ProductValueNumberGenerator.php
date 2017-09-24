<?php

namespace Nidup\Sandbox\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

class ProductValueNumberGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = $this->generator->numberBetween(1, 100);

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
