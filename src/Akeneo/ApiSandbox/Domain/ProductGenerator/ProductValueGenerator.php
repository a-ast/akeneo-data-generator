<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\Product\Value;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): Value;
}
