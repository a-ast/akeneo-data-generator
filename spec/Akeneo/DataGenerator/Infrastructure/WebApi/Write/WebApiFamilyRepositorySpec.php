<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Family;
use Akeneo\DataGenerator\Domain\Model\Family\AttributeRequirements;
use Akeneo\DataGenerator\Domain\Model\Family\Attributes;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\FamilyApi;
use PhpSpec\ObjectBehavior;

class WebApiFamilyRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_a_family (
        $client,
        Family $family,
        Attributes $attributes,
        AttributeRequirements $requirements,
        FamilyApi $api
    ) {
        $family->code()->willReturn('MyFamilyCode');
        $family->attributes()->willReturn($attributes);
        $attributes->getCodes()->willReturn(['MyAttributeCode1', 'MyAttributeCode2']);
        $family->requirements()->willReturn($requirements);
        $requirements->getAttributeCodesPerChannel()->willReturn(
            [
                'ecommerce' => ['MyAttributeCode1', 'MyAttributeCode2'],
                'print' => ['MyAttributeCode1']
            ]
        );


        $client->getFamilyApi()->willReturn($api);
        $api->create(
            'MyFamilyCode',
            [
                'attributes' => ['MyAttributeCode1', 'MyAttributeCode2'],
                'attribute_requirements' => [
                    'ecommerce' => ['MyAttributeCode1', 'MyAttributeCode2'],
                    'print' => ['MyAttributeCode1']
                ]
            ]
        )->shouldBeCalled();

        $this->add($family);
    }
}
