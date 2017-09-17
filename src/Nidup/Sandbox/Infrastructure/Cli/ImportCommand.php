<?php

namespace Nidup\Sandbox\Infrastructure\Cli;

use Akeneo\Pim\AkeneoPimClientBuilder;
use Akeneo\Pim\AkeneoPimClientInterface;
use Nidup\Sandbox\Application\ConfigProvider;
use Nidup\Sandbox\Application\ProductGenerator;
use Nidup\Sandbox\Domain\Attribute;
use Nidup\Sandbox\Domain\AttributeRepository;
use Nidup\Sandbox\Domain\Family;
use Nidup\Sandbox\Domain\FamilyRepository;
use Nidup\Sandbox\Domain\Product;
use Nidup\Sandbox\Infrastructure\Database\InMemoryAttributeRepository;
use Nidup\Sandbox\Infrastructure\Database\InMemoryFamilyRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    protected function configure()
    {
        $this->setName('nidup:sandbox:import')
            ->setDescription('Import through the Akeneo PIM Web API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = $this->getGenerator();
        $product = $generator->generate();
        $this->importProduct($product);
    }

    private function importProduct(Product $product)
    {
        $client = $this->getClient();
        $productData = $product->toArray();
        var_dump($productData);
        $client->getProductApi()->upsert($product->getIdentifier(), $productData);
    }

    private function getGenerator(): ProductGenerator
    {
        $attributeRepository = $this->buildAttributeRepository();
        $familyRepository = $this->buildFamilyRepository($attributeRepository);

        return new ProductGenerator($familyRepository);
    }

    private function buildFamilyRepository(AttributeRepository $attributeRepository): FamilyRepository
    {
        $client = $this->getClient();
        $cursor = $client->getFamilyApi()->all();
        $repository = new InMemoryFamilyRepository();
        foreach ($cursor as $familyData) {
            $attributeCodes = $familyData['attributes'];
            $attributes = [];
            foreach ($attributeCodes as $attributeCode) {
                $attributes[] = $attributeRepository->get($attributeCode);
            }
            $repository->add(new Family($familyData['code'], $attributes));
        }

        return $repository;
    }

    private function buildAttributeRepository(): AttributeRepository
    {
        $client = $this->getClient();
        $cursor = $client->getAttributeApi()->all();
        $repository = new InMemoryAttributeRepository();
        foreach ($cursor as $attributeData) {
            $repository->add(
                new Attribute(
                    $attributeData['code'],
                    $attributeData['type'],
                    $attributeData['localizable'],
                    $attributeData['scopable']
                )
            );
        }

        return $repository;
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $config = new ConfigProvider( __DIR__.'/../../../../../app/parameters.yml');
        $baseUri = sprintf('%s:%s', $config->getParameter('host'), $config->getParameter('port'));
        $clientId = $config->getParameter('client_id');
        $secret = $config->getParameter('secret');
        $username = $config->getParameter('username');
        $password = $config->getParameter('password');

        $clientBuilder = new AkeneoPimClientBuilder($baseUri);
        return $clientBuilder->buildAuthenticatedByPassword(
            $clientId,
            $secret,
            $username,
            $password
        );
    }
}