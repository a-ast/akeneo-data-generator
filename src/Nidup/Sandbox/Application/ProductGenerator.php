<?php

namespace Nidup\Sandbox\Application;

use Faker\Factory;
use Faker\Generator;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;
use Nidup\Sandbox\Domain\Product;
use Nidup\Sandbox\Domain\ProductValue;
use Nidup\Sandbox\Domain\ProductValues;

class ProductGenerator
{
    /** @var Generator */
    private $generator;
    /** @var FamilyRepository */
    private $familyRepository;

    public function __construct(FamilyRepository $familyRepository)
    {
        $this->generator = Factory::create();
        $this->familyRepository = $familyRepository;
    }

    public function generate(): Product
    {
        $identifier = $this->generator->ean13();
        $family = $this->getRandomFamily();
        $values = $this->getRandomValues($family);

        return new Product($identifier, $family, $values, []);
    }

    private function getRandomFamily(): Family
    {
        $families = $this->familyRepository->all();

        return $families[rand(0, count($families) -1 )];
    }

    private function getRandomValues(Family $family): ProductValues
    {
        $attributes = $family->getAttributes();
        $values = new ProductValues();
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getType() === 'pim_catalog_text' && !$attribute->isLocalizable() && !$attribute->isScopable()) {
                $values->addValue($this->generateGlobalTextValue($attribute));
            }
        }

        return $values;
    }

    private function generateGlobalTextValue(Attribute $attribute): ProductValue
    {
        return new ProductValue($attribute, $this->generator->realText($maxNbChars = 100));
    }
}
