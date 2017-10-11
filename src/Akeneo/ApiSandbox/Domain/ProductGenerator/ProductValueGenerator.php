<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\ProductValue;

interface ProductValueGenerator
{
    public function generate(Attribute $attribute, $channelCode, $localeCode): ProductValue;
}
