<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Tests\DependencyInjection\Compiler;

use Nicolassing\SequenceBundle\DependencyInjection\Compiler\HandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class HandlerPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container
            ->register('nicolassing_sequence.handler_chain')
            ->setClass(HandlerPass::class)
            ->setPublic(false)
        ;
        $container
            ->register('foo')
            ->setPublic(false)
            ->addTag('nicolassing_sequence.handler', array('name' => 'foo'));
        ;
        $container
            ->register('bar')
            ->setPublic(false)
        ;
        $handlerPass = new HandlerPass();
        $handlerPass->process($container);

        $this->assertCount(1, $container->getDefinition('nicolassing_sequence.handler_chain')->getMethodCalls());
        $this->assertEquals($container->getDefinition('nicolassing_sequence.handler_chain')->getMethodCalls()[0][1][1], 'foo');
    }
}
