<?php

namespace Akeneo\DataGenerator\Domain\ProductGenerator;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Product\Value;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): Value;
}
