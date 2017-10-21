<?php

namespace spec\Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Api\ChannelApi;
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
        $channel->getCode()->willReturn('MyChannelCode');
        $channel->getLocales()->willReturn([$locale]);
        $locale->getCode()->willReturn('en_US');
        $channel->getCurrencies()->willReturn([$currency]);
        $currency->getCode()->willReturn('EUR');
        $channel->tree()->willReturn($tree);
        $tree->getCode()->willReturn('master');

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
