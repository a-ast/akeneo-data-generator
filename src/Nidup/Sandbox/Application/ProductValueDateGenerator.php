<?php

namespace Nidup\Sandbox\Application;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\ProductValue;

class ProductValueDateGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = $this->generator->date('Y-m-d');

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
