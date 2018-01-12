<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\DataGenerator\Domain\Model\Product;
use Akeneo\DataGenerator\Domain\Model\Product\Value;
use Akeneo\DataGenerator\Domain\Model\Product\Values;
use Akeneo\DataGenerator\Domain\ProductMediaNormalizer;
use PhpSpec\ObjectBehavior;

class ProductMediaNormalizerSpec extends ObjectBehavior
{
    function it_is_a_product_media_normalizer()
    {
        $this->shouldHaveType(ProductMediaNormalizer::class);
    }

    function it_normalizes_a_product_media_value(
        Product $product,
        Values $values,
        Value $nameValue,
        Value $imageValue,
        Attribute $nameAttribute,
        Attribute $imageAttribute
    ) {
        $product->values()->willReturn($values);
        $values->all()->willReturn([$nameValue, $imageValue]);

        $nameValue->getAttribute()->willReturn($nameAttribute);
        $nameAttribute->type()->willReturn(AttributeTypes::TEXT);

        $imageValue->getAttribute()->willReturn($imageAttribute);
        $imageAttribute->type()->willReturn(AttributeTypes::IMAGE);
        $imageAttribute->code()->willReturn('image');
        $imageValue->getData()->willReturn('/tmp/path/image.jpg');
        $imageValue->getLocale()->willReturn('en_US');
        $imageValue->getChannel()->willReturn('ecommerce');

        $this->normalize($product)->shouldReturn([
            'image' => [
                [
                    'data' => '/tmp/path/image.jpg',
                    'locale' => 'en_US',
                    'scope' => 'ecommerce'
                ]
            ]
        ]);
    }
}
