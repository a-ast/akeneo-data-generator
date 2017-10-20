<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

class ProductValueOptionsGenerator implements ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): Value
    {
        $options = $attribute->getOptions();
        $codes = $options->getCodes();
        $randomCodes = [];
        for ($ind = 0; $ind < 3; $ind++) {
            $randomCodes[] = $codes[rand(0, count($codes) -1)];
        }
        $data = array_unique($randomCodes);

        return new Value($attribute, $data, $localeCode, $channelCode);
    }
}
