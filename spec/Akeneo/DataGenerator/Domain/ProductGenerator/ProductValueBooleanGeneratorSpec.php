<?php

namespace spec\Akeneo\DataGenerator\Domain\ProductGenerator;

use Akeneo\DataGenerator\Domain\Exception\NoChildrenCategoryDefinedException;
use Akeneo\DataGenerator\Domain\Exception\NoFamilyDefinedException;
use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\Attribute\Properties;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\FamilyRepository;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use Akeneo\DataGenerator\Domain\Model\Product\Value;
use PhpSpec\ObjectBehavior;

class ProductValueBooleanGeneratorSpec extends ObjectBehavior
{
    function it_generates_a_global_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, null, null);
        $productValue->shouldBeAnInstanceOf(Value::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn(null);
        $productValue->getChannel()->shouldReturn(null);
    }

    function it_generates_a_localized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, null, 'fr_FR');
        $productValue->shouldBeAnInstanceOf(Value::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn('fr_FR');
        $productValue->getChannel()->shouldReturn(null);
    }

    function it_generates_a_channelized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, 'ecommerce', null);
        $productValue->shouldBeAnInstanceOf(Value::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn(null);
        $productValue->getChannel()->shouldReturn('ecommerce');
    }

    function it_generates_a_localized_and_channelized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, 'ecommerce', 'fr_FR');
        $productValue->shouldBeAnInstanceOf(Value::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn('fr_FR');
        $productValue->getChannel()->shouldReturn('ecommerce');
    }

    public function getMatchers(): array
    {
        return [
            'haveBooleanData' => function ($subject) {
                return $subject->getData() === true || $subject->getData() === false;
            },
        ];
    }
}
