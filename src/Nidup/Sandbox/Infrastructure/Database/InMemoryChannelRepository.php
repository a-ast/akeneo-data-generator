<?php

namespace Nidup\Sandbox\Infrastructure\Database;

use Nidup\Sandbox\Domain\Channel;
use Nidup\Sandbox\Domain\ChannelRepository;

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
            throw new \Exception(sprintf("Channel %s does not exists", $code));
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
