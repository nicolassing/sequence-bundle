<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberGenerator;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Nicolassing\SequenceBundle\Factory\SequenceFactoryInterface;
use Nicolassing\SequenceBundle\Handler\HandlerChain;
use Nicolassing\SequenceBundle\Model\Sequence;
use Nicolassing\SequenceBundle\Model\SequenceInterface;

final class NumberGenerator implements NumberGeneratorInterface
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

    /**
     * @var HandlerChain
     */
    private $handlerChain;

    /**
     * @var array
     */
    private $sequences = [];

    public function __construct(
        ObjectRepository $sequenceRepository,
        SequenceFactoryInterface $sequenceFactory,
        EntityManagerInterface $entityManager,
        HandlerChain $handlerChain
    ) {
        $this->sequenceRepository = $sequenceRepository;
        $this->sequenceFactory = $sequenceFactory;
        $this->entityManager = $entityManager;
        $this->handlerChain = $handlerChain;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($object, string $type): string
    {
        $handler = $this->handlerChain->getHandler($type);
        $prefix = $handler->getPrefix($object);
        $sequence = $this->getSequence($type, $prefix);
        $this->entityManager->lock($sequence, LockMode::OPTIMISTIC, $sequence->getVersion());

        return $handler->format($object, $sequence->getIndex());
    }

    /**
     * @param string $type
     * @param null|string $prefix
     *
     * @return SequenceInterface
     */
    private function getSequence(string $type, ?string $prefix = null): SequenceInterface
    {
        if (isset($this->sequences[$type.$prefix])) {
            $sequence = $this->sequences[$type.$prefix];
            $sequence->incrementIndex();
            $this->entityManager->persist($sequence);

            return $sequence;
        }

        /** @var SequenceInterface $sequence */
        $sequence = $this->sequenceRepository->findOneBy(['type' => $type, 'prefix' => $prefix]);

        if (null === $sequence) {
            $sequence = $this->sequenceFactory->createNew($type, $prefix);
        }

        $sequence->incrementIndex();
        $this->entityManager->persist($sequence);
        $this->sequences[$type.$prefix] = $sequence;

        return $sequence;
    }
}
