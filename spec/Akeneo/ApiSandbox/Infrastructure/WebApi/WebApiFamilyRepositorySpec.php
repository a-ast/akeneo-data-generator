<?php

namespace spec\Akeneo\ApiSandbox\Infrastructure\WebApi;

use Akeneo\ApiSandbox\Domain\Model\Family;
use Akeneo\ApiSandbox\Domain\Model\FamilyAttributeRequirements;
use Akeneo\ApiSandbox\Domain\Model\FamilyAttributes;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Api\FamilyApi;
use PhpSpec\ObjectBehavior;

class WebApiFamilyRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_a_family (
        $client,
        Family $family,
        FamilyAttributes $attributes,
        FamilyAttributeRequirements $requirements,
        FamilyApi $api
    ) {
        $family->getCode()->willReturn('MyFamilyCode');
        $family->getAttributes()->willReturn($attributes);
        $attributes->getCodes()->willReturn(['MyAttributeCode1', 'MyAttributeCode2']);
        $family->getRequirements()->willReturn($requirements);
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
