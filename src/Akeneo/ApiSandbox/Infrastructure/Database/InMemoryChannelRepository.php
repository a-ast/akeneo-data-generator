<?php

namespace Akeneo\ApiSandbox\Infrastructure\Database;

use Akeneo\ApiSandbox\Domain\Model\Channel;
use Akeneo\ApiSandbox\Domain\Model\ChannelRepository;
use Akeneo\ApiSandbox\Infrastructure\Database\Exception\EntityDoesNotExistsException;

class InMemoryChannelRepository implements ChannelRepository
{
    private $items = [];

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $code): Channel
    {
        if (!isset($this->items[$code])) {
            throw new EntityDoesNotExistsException(sprintf("Channel %s does not exists", $code));
        }

        return $this->items[$code];
    }

    public function add(Channel $item)
    {
        $this->items[$item->getCode()] = $item;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function all(): array
    {
        return array_values($this->items);
    }
}
