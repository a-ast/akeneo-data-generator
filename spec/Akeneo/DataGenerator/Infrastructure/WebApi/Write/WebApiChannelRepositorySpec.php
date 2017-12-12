<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\ChannelApi;
use PhpSpec\ObjectBehavior;

class WebApiChannelRepositorySpec extends ObjectBehavior
{
    function let(AkeneoPimClientInterface $client) {
        $this->beConstructedWith($client);
    }

    function it_stores_a_channel (
        $client,
        Channel $channel,
        Locale $locale,
        Currency $currency,
        Category $tree,
        ChannelApi $api
    ) {
        $channel->code()->willReturn('MyChannelCode');
        $channel->locales()->willReturn([$locale]);
        $locale->code()->willReturn('en_US');
        $channel->currencies()->willReturn([$currency]);
        $currency->code()->willReturn('EUR');
        $channel->tree()->willReturn($tree);
        $tree->code()->willReturn('master');

        $client->getChannelApi()->willReturn($api);
        $api->create(
            'MyChannelCode',
            [
                'locales' => ['en_US'],
                'currencies' => ['EUR'],
                'category_tree' => 'master',
            ]
        )->shouldBeCalled();

        $this->add($channel);
    }
}
