<?php

namespace Nidup\Sandbox\Domain;

interface ChannelRepository
{
    public function get(string $code): Channel;
    public function add(Channel $channel);
    public function count(): int;
    public function all(): array;
}
