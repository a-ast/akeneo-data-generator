<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Attribute;
use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\DataGenerator\Domain\Model\AttributeTypes;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Api\AttributeApi;
use Akeneo\Pim\Api\AttributeOptionApi;
use PhpSpec\ObjectBehavior;

class WebApiAttributeRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_a_text_attribute (
        $client,
        Attribute $attribute,
        AttributeGroup $group,
        Attribute\Properties $properties,
        Attribute\Options $options,
        AttributeApi $attributeApi,
        AttributeOptionApi $attributeOptionApi
    ) {
        $attribute->getCode()->willReturn('MyAttributeCode');
        $attribute->getType()->willReturn(AttributeTypes::TEXT);
        $attribute->isLocalizable()->willReturn(true);
        $attribute->isScopable()->willReturn(false);
        $attribute->getGroup()->willReturn($group);
        $attribute->getProperties()->willReturn($properties);
        $properties->all()->willReturn([]);
        $group->getCode()->willReturn('MyGroupCode');
        $client->getAttributeApi()->willReturn($attributeApi);
        $attributeApi->create(
            'MyAttributeCode',
            [
                'type' => AttributeTypes::TEXT,
                'localizable' => true,
                'scopable' => false,
                'group' => 'MyGroupCode'
            ]
        )->shouldBeCalled();

        $attribute->getOptions()->willReturn($options);
        $options->getCodes()->willReturn([]);
        $client->getAttributeOptionApi()->willReturn($attributeOptionApi);
        $attributeOptionApi->create()->shouldNotBeCalled();

        $this->add($attribute);
    }

    function it_stores_a_simple_select_attribute (
        $client,
        Attribute $attribute,
        AttributeGroup $group,
        Attribute\Properties $properties,
        Attribute\Options $options,
        AttributeApi $attributeApi,
        AttributeOptionApi $attributeOptionApi
    ) {
        $attribute->getCode()->willReturn('MyAttributeCode');
        $attribute->getType()->willReturn(AttributeTypes::OPTION_SIMPLE_SELECT);
        $attribute->isLocalizable()->willReturn(false);
        $attribute->isScopable()->willReturn(false);
        $attribute->getGroup()->willReturn($group);
        $attribute->getProperties()->willReturn($properties);
        $properties->all()->willReturn([]);
        $group->getCode()->willReturn('MyGroupCode');
        $client->getAttributeApi()->willReturn($attributeApi);
        $attributeApi->create(
            'MyAttributeCode',
            [
                'type' => AttributeTypes::OPTION_SIMPLE_SELECT,
                'localizable' => false,
                'scopable' => false,
                'group' => 'MyGroupCode'
            ]
        )->shouldBeCalled();

        $attribute->getOptions()->willReturn($options);
        $options->getCodes()->willReturn(['MyOptionCode']);
        $client->getAttributeOptionApi()->willReturn($attributeOptionApi);
        $attributeOptionApi->create(
            'MyAttributeCode',
            'MyOptionCode'
        )->shouldBeCalled();

        $this->add($attribute);
    }
}
