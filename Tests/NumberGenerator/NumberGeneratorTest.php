<?php

namespace Nicolassing\SequenceBundle\Tests\NumberGenerator;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nicolassing\SequenceBundle\Factory\SequenceFactoryInterface;
use Nicolassing\SequenceBundle\Handler\DefaultHandler;
use Nicolassing\SequenceBundle\Handler\HandlerChain;
use Nicolassing\SequenceBundle\Model\SequenceInterface;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGenerator;
use PHPUnit\Framework\TestCase;

class NumberGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $sequence = $this->createMock(SequenceInterface::class);
        $sequence->expects($this->once())->method('getIndex')->willReturn(1);

        $objectRepository = $this->createMock(ObjectRepository::class);
        $objectRepository->expects($this->once())->method('findOneBy')->willReturn($sequence);

        $sequenceFactory = $this->createMock(SequenceFactoryInterface::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('lock');

        $handler = $this->createMock(DefaultHandler::class);
        $handler->expects($this->once())->method('getPrefix')->willReturn('NA');
        $handler->expects($this->once())->method('format')->willReturn('NA000001');

        $handlerChain = $this->createMock(HandlerChain::class);
        $handlerChain->expects($this->once())->method('getHandler')->willReturn($handler);

        $object = new class {
        };

        $numberGenerator = new NumberGenerator($objectRepository, $sequenceFactory, $entityManager, $handlerChain);
        $number = $numberGenerator->generate($object, 'test');

        $this->assertEquals('NA000001', $number);
    }
}
