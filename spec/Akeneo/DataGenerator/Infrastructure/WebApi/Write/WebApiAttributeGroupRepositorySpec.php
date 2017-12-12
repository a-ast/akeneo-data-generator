<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\AttributeGroup;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApi;
use PhpSpec\ObjectBehavior;

class WebApiAttributeGroupRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_an_attribute_group (
        $client,
        AttributeGroup $group,
        AttributeGroupApi $groupApi
    ) {
        $group->code()->willReturn('MyAttributeGroupCode');

        $client->getAttributeGroupApi()->willReturn($groupApi);
        $groupApi->create(
            'MyAttributeGroupCode',
            []
        )->shouldBeCalled();

        $this->add($group);
    }
}
