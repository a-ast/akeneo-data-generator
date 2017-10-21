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
        $attribute->code()->willReturn('MyAttributeCode');
        $attribute->type()->willReturn(AttributeTypes::TEXT);
        $attribute->localizable()->willReturn(true);
        $attribute->scopable()->willReturn(false);
        $attribute->group()->willReturn($group);
        $attribute->properties()->willReturn($properties);
        $properties->all()->willReturn([]);
        $group->code()->willReturn('MyGroupCode');
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

        $attribute->options()->willReturn($options);
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
        $attribute->code()->willReturn('MyAttributeCode');
        $attribute->type()->willReturn(AttributeTypes::OPTION_SIMPLE_SELECT);
        $attribute->localizable()->willReturn(false);
        $attribute->scopable()->willReturn(false);
        $attribute->group()->willReturn($group);
        $attribute->properties()->willReturn($properties);
        $properties->all()->willReturn([]);
        $group->code()->willReturn('MyGroupCode');
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

        $attribute->options()->willReturn($options);
        $options->getCodes()->willReturn(['MyOptionCode']);
        $client->getAttributeOptionApi()->willReturn($attributeOptionApi);
        $attributeOptionApi->create(
            'MyAttributeCode',
            'MyOptionCode'
        )->shouldBeCalled();

        $this->add($attribute);
    }
}
