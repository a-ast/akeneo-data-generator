<?php

namespace Nidup\Sandbox\Application;

use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeTypes;

class ProductValueGeneratorRegistry
{
    private $generators = [];

    public function __construct()
    {
        $this->generators = [];
        $textGenerator = new ProductValueTextGenerator();
        $this->generators[AttributeTypes::TEXT] = $textGenerator;
        $this->generators[AttributeTypes::TEXTAREA] = $textGenerator;
        $this->generators[AttributeTypes::OPTION_SIMPLE_SELECT] = new ProductValueOptionGenerator();
        $this->generators[AttributeTypes::OPTION_MULTI_SELECT] = new ProductValueOptionsGenerator();
        $this->generators[AttributeTypes::BOOLEAN] = new ProductValueBooleanGenerator();
        $this->generators[AttributeTypes::DATE] = new ProductValueDateGenerator();
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
