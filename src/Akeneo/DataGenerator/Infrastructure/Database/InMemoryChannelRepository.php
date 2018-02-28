<?php

namespace Akeneo\DataGenerator\Infrastructure\Database;

use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\ChannelRepository;
use Akeneo\DataGenerator\Infrastructure\Database\Exception\EntityDoesNotExistsException;

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
        $this->items[$item->code()] = $item;
    }

    public function upsert(Channel $item)
    {
        $this->items[$item->code()] = $item;
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
