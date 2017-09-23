<?php

namespace Nidup\Sandbox\Domain\ProductGenerator;

use Nidup\Sandbox\Domain\Model\Attribute;
use Nidup\Sandbox\Domain\Model\ProductValue;

class ProductValueBooleanGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue
    {
        $data = rand(0, 1) == 1;

        return new ProductValue($attribute, $data, $localeCode, $channelCode);
    }
}
