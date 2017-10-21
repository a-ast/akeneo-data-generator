<?php

namespace Akeneo\DataGenerator\Infrastructure\WebApi\Write;

use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
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
        foreach ($channel->getLocales() as $locale) {
            $localeCodes[]= $locale->getCode();
        }
        $currencyCodes = [];
        foreach ($channel->getCurrencies() as $currency) {
            $currencyCodes[]= $currency->getCode();
        }
        $channelData = [
            'locales' => $localeCodes,
            'currencies' => $currencyCodes,
            'category_tree' => $channel->tree()->getCode()
        ];
        $this->client->getChannelApi()->create($channel->getCode(), $channelData);
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
