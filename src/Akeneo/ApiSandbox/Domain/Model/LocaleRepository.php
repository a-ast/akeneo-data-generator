<?php

namespace Akeneo\ApiSandbox\Domain\Model;

interface LocaleRepository
{
    public function get(string $code): Locale;
    public function add(Locale $locale);
    public function count(): int;
    public function all(): array;
}
