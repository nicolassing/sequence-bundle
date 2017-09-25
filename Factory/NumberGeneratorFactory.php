<?php

namespace Nicolassing\SequenceBundle\Factory;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterInterface;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGenerator;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGeneratorInterface;

class NumberGeneratorFactory implements NumberGeneratorFactoryInterface
{
    /**
     * @var ObjectRepository
     */
    private $sequenceRepository;

    /**
     * @var SequenceFactoryInterface
     */
    private $sequenceFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        ObjectRepository $sequenceRepository,
        SequenceFactoryInterface $sequenceFactory,
        EntityManager $entityManager
    ) {
        $this->sequenceRepository = $sequenceRepository;
        $this->sequenceFactory = $sequenceFactory;
        $this->entityManager = $entityManager;
    }


    public function createNew(
        NumberFormatterInterface $numberFormatter,
        PrefixFormatterInterface $prefixFormatter
    ): NumberGeneratorInterface {
        return new NumberGenerator(
            $this->sequenceRepository,
            $this->sequenceFactory,
            $this->entityManager,
            $numberFormatter,
            $prefixFormatter
        );
    }

}