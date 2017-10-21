<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

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
        $tree->code()->willReturn('MyTreeCode');
        $tree->parent()->willReturn(null);
        $tree->children()->willReturn([$child]);

        $child->code()->willReturn('MyChildCode');
        $child->parent()->willReturn($tree);
        $child->children()->willReturn([$granChild]);

        $granChild->code()->willReturn('MyGranChildCode');
        $granChild->parent()->willReturn($child);
        $granChild->children()->willReturn([]);

        $client->getCategoryApi()->willReturn($api);

        $api->create('MyTreeCode', ['parent' => null])->shouldBeCalled();
        $api->create('MyChildCode', ['parent' => 'MyTreeCode'])->shouldBeCalled();
        $api->create('MyGranChildCode', ['parent' => 'MyChildCode'])->shouldBeCalled();

        $this->add($tree);
        $this->add($child);
        $this->add($granChild);
    }
}
