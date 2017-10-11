<?php

namespace spec\Akeneo\Sandbox\Domain\ProductGenerator;

use Akeneo\Sandbox\Domain\Exception\NoChildrenCategoryDefinedException;
use Akeneo\Sandbox\Domain\Exception\NoFamilyDefinedException;
use Akeneo\Sandbox\Domain\Model\Attribute;
use Akeneo\Sandbox\Domain\Model\AttributeProperties;
use Akeneo\Sandbox\Domain\Model\Category;
use Akeneo\Sandbox\Domain\Model\CategoryRepository;
use Akeneo\Sandbox\Domain\Model\ChannelRepository;
use Akeneo\Sandbox\Domain\Model\CurrencyRepository;
use Akeneo\Sandbox\Domain\Model\Family;
use Akeneo\Sandbox\Domain\Model\FamilyRepository;
use Akeneo\Sandbox\Domain\Model\LocaleRepository;
use Akeneo\Sandbox\Domain\Model\ProductValue;
use PhpSpec\ObjectBehavior;

class ProductValueBooleanGeneratorSpec extends ObjectBehavior
{
    function it_generates_a_global_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, null, null);
        $productValue->shouldBeAnInstanceOf(ProductValue::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn(null);
        $productValue->getChannel()->shouldReturn(null);
    }

    function it_generates_a_localized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, null, 'fr_FR');
        $productValue->shouldBeAnInstanceOf(ProductValue::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn('fr_FR');
        $productValue->getChannel()->shouldReturn(null);
    }

    function it_generates_a_channelized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, 'ecommerce', null);
        $productValue->shouldBeAnInstanceOf(ProductValue::class);
        $productValue->shouldHaveBooleanData();
        $productValue->getLocale()->shouldReturn(null);
        $productValue->getChannel()->shouldReturn('ecommerce');
    }

    function it_generates_a_localized_and_channelized_boolean_product_value (
        Attribute $enabled
    ) {
        $productValue = $this->generate($enabled, 'ecommerce', 'fr_FR');
        $productValue->shouldBeAnInstanceOf(ProductValue::class);
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
