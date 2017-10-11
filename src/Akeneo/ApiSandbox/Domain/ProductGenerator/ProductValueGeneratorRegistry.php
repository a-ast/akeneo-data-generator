<?php

namespace Akeneo\ApiSandbox\Domain\ProductGenerator;

use Akeneo\ApiSandbox\Domain\Model\Attribute;
use Akeneo\ApiSandbox\Domain\Model\AttributeTypes;
use Akeneo\ApiSandbox\Domain\Model\CurrencyRepository;

class ProductValueGeneratorRegistry
{
    private $generators = [];

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->generators = [];
        $textGenerator = new ProductValueTextGenerator();
        $this->generators[AttributeTypes::TEXT] = $textGenerator;
        $this->generators[AttributeTypes::TEXTAREA] = $textGenerator;
        $this->generators[AttributeTypes::OPTION_SIMPLE_SELECT] = new ProductValueOptionGenerator();
        $this->generators[AttributeTypes::OPTION_MULTI_SELECT] = new ProductValueOptionsGenerator();
        $this->generators[AttributeTypes::BOOLEAN] = new ProductValueBooleanGenerator();
        $this->generators[AttributeTypes::DATE] = new ProductValueDateGenerator();
        $this->generators[AttributeTypes::PRICE_COLLECTION] = new ProductValuePriceGenerator($currencyRepository);
        $this->generators[AttributeTypes::METRIC] = new ProductValueMetricGenerator();
        $this->generators[AttributeTypes::NUMBER] = new ProductValueNumberGenerator();
        $this->generators[AttributeTypes::IMAGE] = new ProductValueImageGenerator();
    }

    public function support(Attribute $attribute): bool
    {
        return isset($this->generators[$attribute->getType()]);
    }

    public function get(Attribute $attribute): ProductValueGenerator
    {
        return $this->generators[$attribute->getType()];
    }
}
