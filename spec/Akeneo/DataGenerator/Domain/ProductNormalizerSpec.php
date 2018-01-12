<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\Product\Categories;
use Akeneo\DataGenerator\Domain\Model\Product\Value;
use Akeneo\DataGenerator\Domain\Model\Product\Values;
use Akeneo\DataGenerator\Domain\ProductNormalizer;
use PhpSpec\ObjectBehavior;

class ProductNormalizerSpec extends ObjectBehavior
{
    function it_is_a_product_normalizer()
    {
        $this->shouldHaveType(ProductNormalizer::class);
    }

    function it_normalizes_a_product_without_images(
        Product $product,
        Family $family,
        Values $values,
        Value $colorValue,
        Value $nameValue,
        Value $imageValue,
        Attribute $colorAttribute,
        Attribute $nameAttribute,
        Attribute $imageAttribute,
        Categories $categories,
        Category $shoesCategory,
        Category $bootsCategory
    ) {
        $product->identifier()->willReturn('blue boots');
        $product->family()->willReturn($family);
        $product->values()->willReturn($values);
        $product->categories()->willReturn($categories);

        $family->code()->willReturn('shoes');
        $values->all()->willReturn([$colorValue, $nameValue, $imageValue]);

        $colorValue->getAttribute()->willReturn($colorAttribute);
        $colorValue->getData()->willReturn('blue');
        $colorValue->getLocale()->willReturn('en_US');
        $colorValue->getChannel()->willReturn('ecommerce');
        $colorAttribute->type()->willReturn(AttributeTypes::OPTION_SIMPLE_SELECT);
        $colorAttribute->code()->willReturn('color');

        $nameValue->getAttribute()->willReturn($nameAttribute);
        $nameValue->getData()->willReturn('Amazing blue boots');
        $nameValue->getLocale()->willReturn('en_US');
        $nameValue->getChannel()->willReturn('ecommerce');
        $nameAttribute->type()->willReturn(AttributeTypes::TEXT);
        $nameAttribute->code()->willReturn('name');

        $imageValue->getAttribute()->willReturn($imageAttribute);
        $imageAttribute->type()->willReturn(AttributeTypes::IMAGE);

        $categories->all()->willReturn([$shoesCategory, $bootsCategory]);
        $shoesCategory->code()->willReturn('shoes');
        $bootsCategory->code()->willReturn('boots');

        $this->normalize($product)->shouldReturn([
            'identifier' => 'blue boots',
            'family' => 'shoes',
            'values' => [
                'color' => [
                    [
                        'data' => 'blue',
                        'locale' => 'en_US',
                        'scope' => 'ecommerce'
                    ]
                ],
                'name' => [
                    [
                        'data' => 'Amazing blue boots',
                        'locale' => 'en_US',
                        'scope' => 'ecommerce'
                    ]
                ]
            ],
            'categories' => ['shoes', 'boots']
        ]);
    }
}
