<?php

namespace Nidup\Sandbox\Domain;

interface CurrencyRepository
{
    public function get(string $code): Currency;
    public function add(Currency $currency);
    public function count(): int;
    public function all(): array;
}
