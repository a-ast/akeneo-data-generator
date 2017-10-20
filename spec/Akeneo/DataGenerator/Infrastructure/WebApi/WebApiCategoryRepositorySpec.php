<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Api\CategoryApi;
use PhpSpec\ObjectBehavior;

class WebApiCategoryRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_categories (
        $client,
        Category $tree,
        Category $child,
        Category $granChild,
        CategoryApi $api
    ) {
        $tree->getCode()->willReturn('MyTreeCode');
        $tree->getParent()->willReturn(null);
        $tree->getChildren()->willReturn([$child]);

        $child->getCode()->willReturn('MyChildCode');
        $child->getParent()->willReturn($tree);
        $child->getChildren()->willReturn([$granChild]);

        $granChild->getCode()->willReturn('MyGranChildCode');
        $granChild->getParent()->willReturn($child);
        $granChild->getChildren()->willReturn([]);

        $client->getCategoryApi()->willReturn($api);

        $api->create('MyTreeCode', ['parent' => null])->shouldBeCalled();
        $api->create('MyChildCode', ['parent' => 'MyTreeCode'])->shouldBeCalled();
        $api->create('MyGranChildCode', ['parent' => 'MyChildCode'])->shouldBeCalled();

        $this->add($tree);
        $this->add($child);
        $this->add($granChild);
    }
}
