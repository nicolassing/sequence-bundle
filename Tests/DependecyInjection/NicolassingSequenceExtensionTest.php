<?php

namespace Nicolassing\SequenceBundle\Tests\DependencyInjection;

use Nicolassing\SequenceBundle\DependencyInjection\NicolassingSequenceExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class NicolassingSequenceExtensionTest extends TestCase
{
    public function testShouldAddDefaultHandler()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/fixtures/config.yml'));
        $containerBuilder = new ContainerBuilder();
        $extension = new NicolassingSequenceExtension();
        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('nicolassing_sequence.handler.test'));
    }

    public function testShouldAddServiceHandler()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/fixtures/config_service.yml'));
        $containerBuilder = new ContainerBuilder();
        $extension = new NicolassingSequenceExtension();
        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->hasAlias('nicolassing_sequence.handler.test'));
    }
}
