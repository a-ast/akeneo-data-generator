<?php

namespace Akeneo\Sandbox\Domain\Model;

interface ChannelRepository
{
    public function get(string $code): Channel;
    public function add(Channel $channel);
    public function count(): int;
    public function all(): array;
}
