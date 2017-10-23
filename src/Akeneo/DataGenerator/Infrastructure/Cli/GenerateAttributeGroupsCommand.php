<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli;

use Akeneo\DataGenerator\Application\GenerateAttributeGroup;
use Akeneo\DataGenerator\Application\GenerateAttributeGroupHandler;
use Akeneo\DataGenerator\Domain\AttributeGroupGenerator;
use Akeneo\DataGenerator\Infrastructure\Cli\ApiClient\ApiClientFactory;
use Akeneo\DataGenerator\Infrastructure\WebApi\WriteRepositories;
use Akeneo\Pim\AkeneoPimClientInterface;
use Akeneo\Pim\Exception\HttpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAttributeGroupsCommand extends Command
{
    protected function configure()
    {
        $this->setName('akeneo:api:generate-attribute-groups')
            ->setDescription('Import generated attribute groups through the Akeneo PIM Web API')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of groups to generate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $handler = $this->attributeGroupHandler();
        for ($index = 0; $index < $number; $index++) {
            $command = new GenerateAttributeGroup();
            try {
                $handler->handle($command);
            } catch (HttpException $e) {
                echo $e->getMessage();
            }
            $output->writeln(sprintf('<info>%s attribute groups have been generated and imported</info>', $index+1));
        }
    }

    private function attributeGroupHandler(): GenerateAttributeGroupHandler
    {
        $generator = new AttributeGroupGenerator();
        $writeRepositories = new WriteRepositories($this->getClient());
        $groupRepository = $writeRepositories->attributeGroupRepository();

        return new GenerateAttributeGroupHandler($generator, $groupRepository);
    }

    private function getClient(): AkeneoPimClientInterface
    {
        $factory = new ApiClientFactory();
        return $factory->create();
    }
}
