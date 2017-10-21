<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\Pim\AkeneoPimClientInterface;

class WebApiChannelRepository implements ChannelRepository
{
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    public function get(string $code): Channel
    {
        throw new \LogicException('not implemented yet');
    }

    public function add(Channel $channel)
    {
        $localeCodes = [];
        /**
         * @var Locale $locale
         */
        foreach ($channel->locales() as $locale) {
            $localeCodes[]= $locale->code();
        }
        $currencyCodes = [];
        /**
         * @var Currency $currency
         */
        foreach ($channel->currencies() as $currency) {
            $currencyCodes[]= $currency->code();
        }
        $channelData = [
            'locales' => $localeCodes,
            'currencies' => $currencyCodes,
            'category_tree' => $channel->tree()->code()
        ];
        $this->client->getChannelApi()->create($channel->code(), $channelData);
    }

    public function count(): int
    {
        throw new \LogicException('not implemented yet');
    }

    public function all(): array
    {
        throw new \LogicException('not implemented yet');
    }
}
