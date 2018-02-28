<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\CategoryApi;
use PhpSpec\ObjectBehavior;

class WebApiCategoryRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_adds_a_category(
        $client,
        CategoryApi $api
    ) {
        $tree = new Category('MyTreeCode', null);
        $child = new Category('MyChildCode', $tree);
        $granChild = new Category('MyGranChildCode', $child);
        $tree->addChild($child);
        $child->addChild($granChild);

        $client->getCategoryApi()->willReturn($api);

        $api->create('MyTreeCode', ['parent' => null])->shouldBeCalled();
        $api->create('MyChildCode', ['parent' => 'MyTreeCode'])->shouldBeCalled();
        $api->create('MyGranChildCode', ['parent' => 'MyChildCode'])->shouldBeCalled();

        $this->add($tree);
        $this->add($child);
        $this->add($granChild);
    }

    function it_upserts_a_category(
        $client,
        CategoryApi $api
    ) {
        $tree = new Category('MyTreeCode', null);
        $child = new Category('MyChildCode', $tree);
        $granChild = new Category('MyGranChildCode', $child);
        $tree->addChild($child);
        $child->addChild($granChild);

        $client->getCategoryApi()->willReturn($api);

        $api->upsert('MyTreeCode', ['parent' => null])->shouldBeCalled();
        $api->upsert('MyChildCode', ['parent' => 'MyTreeCode'])->shouldBeCalled();
        $api->upsert('MyGranChildCode', ['parent' => 'MyChildCode'])->shouldBeCalled();

        $this->upsert($tree);
        $this->upsert($child);
        $this->upsert($granChild);
    }

    function it_upserts_several_categories(
        $client,
        Category $master,
        Category $sales,
        CategoryApi $api
    ) {
        $master = new Category('master', null);
        $sales = new Category('sales', $master);

        $client->getCategoryApi()->willReturn($api);

        $api->upsertList([
            [
                'code' => 'master',
                'parent' => null,
            ],
            [
                'code' => 'sales',
                'parent' => null,
            ]
        ]);

        $this->upsertMany([$master, $sales]);
    }
}
