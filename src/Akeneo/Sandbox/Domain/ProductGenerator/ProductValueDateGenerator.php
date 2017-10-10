<?php

namespace Akeneo\Sandbox\Domain\ProductGenerator;

use Faker\Factory;
use Faker\Generator;
use Akeneo\Sandbox\Domain\Model\Attribute;
use Akeneo\Sandbox\Domain\Model\ProductValue;

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
