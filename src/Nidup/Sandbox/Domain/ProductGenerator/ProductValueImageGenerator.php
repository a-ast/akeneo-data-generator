<?php

namespace Nidup\Sandbox\Domain\ProductGenerator;

use Faker\Factory;
use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

class ProductValueImageGenerator implements ProductValueGenerator
{
    /** @var Generator */
    private $generator;

    public function __construct()
    {
        $this->generator = Factory::create();
    }

    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = $this->generator->image('/tmp/', 640, 480);

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
