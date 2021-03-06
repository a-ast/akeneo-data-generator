<?php

namespace Akeneo\DataGenerator\Domain\Model;

interface LocaleRepository
{
    public function get(string $code): Locale;
    public function add(Locale $locale);
    public function count(): int;
    public function all(): array;
    public function allEnabled(): array;
}
