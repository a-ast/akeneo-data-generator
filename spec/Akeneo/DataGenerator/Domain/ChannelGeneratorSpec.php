<?php

namespace spec\Akeneo\DataGenerator\Domain;

use Akeneo\DataGenerator\Domain\Model\Category;
use Akeneo\DataGenerator\Domain\Model\CategoryRepository;
use Akeneo\DataGenerator\Domain\Model\Channel;
use Akeneo\DataGenerator\Domain\Model\Currency;
use Akeneo\DataGenerator\Domain\Model\CurrencyRepository;
use Akeneo\DataGenerator\Domain\Model\Locale;
use Akeneo\DataGenerator\Domain\Model\LocaleRepository;
use PhpSpec\ObjectBehavior;

class ChannelGeneratorSpec extends ObjectBehavior
{
    function let(
        LocaleRepository $localeRepository,
        CurrencyRepository $currencyRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->beConstructedWith($localeRepository, $currencyRepository, $categoryRepository);
    }

    function it_generates_a_channel_with_defined_code_locales_and_currencies (
        $localeRepository,
        $currencyRepository,
        $categoryRepository,
        Locale $locale,
        Currency $currency,
        Category $tree
    ) {
        $localeRepository->get('en_US')->willReturn($locale);
        $currencyRepository->get('EUR')->willReturn($currency);
        $categoryRepository->allTrees()->willReturn([$tree]);

        $this->generateWithCode('ecommerce', ['en_US'], ['EUR'], $tree)->shouldBeAnInstanceOf(Channel::class);
    }

    function it_generates_a_channel (
        $localeRepository,
        $currencyRepository,
        $categoryRepository,
        Locale $locale,
        Locale $locale2,
        Currency $currency,
        Currency $currency2,
        Category $tree
    ) {
        $localeRepository->all()->willReturn([$locale, $locale2]);
        $localeRepository->get('en_US')->willReturn($locale);
        $localeRepository->get('fr_FR')->willReturn($locale2);
        $currencyRepository->all()->willReturn([$currency, $currency2]);
        $currencyRepository->get('EUR')->willReturn($currency);
        $currencyRepository->get('USD')->willReturn($currency2);
        $categoryRepository->allTrees()->willReturn([$tree]);
        $locale->code()->willReturn('en_US');
        $locale2->code()->willReturn('fr_FR');
        $currency->code()->willReturn('EUR');
        $currency2->code()->willReturn('USD');

        $this->generate(2, 2)->shouldBeAnInstanceOf(Channel::class);
    }
}
