<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberGenerator;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Nicolassing\SequenceBundle\Factory\SequenceFactoryInterface;
use Nicolassing\SequenceBundle\Model\SequenceInterface;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterInterface;

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
     * @var NumberFormatterInterface
     */
    private $numberFormatter;

    /**
     * @var PrefixFormatterInterface
     */
    private $prefixFormatter;

    public function __construct(
        ObjectRepository $sequenceRepository,
        SequenceFactoryInterface $sequenceFactory,
        EntityManager $entityManager,
        NumberFormatterInterface $numberFormatter,
        PrefixFormatterInterface $prefixFormatter
    ) {
        $this->sequenceRepository = $sequenceRepository;
        $this->sequenceFactory = $sequenceFactory;
        $this->entityManager = $entityManager;
        $this->numberFormatter = $numberFormatter;
        $this->prefixFormatter = $prefixFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($object, string $type): string
    {
        $prefix = $this->prefixFormatter->format($object);
        $sequence = $this->getSequence($type, $prefix);
        $this->entityManager->lock($sequence, LockMode::OPTIMISTIC, $sequence->getVersion());
        $sequence->incrementIndex();
        $this->entityManager->persist($sequence);
        $this->entityManager->flush($sequence);

        return $this->generateNumber($object, $sequence->getIndex(), $prefix);
    }

    /**
     * @param $object
     * @param int $index
     * @param string $prefix
     *
     * @return string
     */
    private function generateNumber($object, int $index, ?string $prefix): string
    {
        return $prefix . $this->numberFormatter->format($object, $index);
    }

    /**
     * @param string $type
     * @param null|string $prefix
     *
     * @return SequenceInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getSequence(string $type, ?string $prefix = null): SequenceInterface
    {
        /** @var SequenceInterface $sequence */
        $sequence = $this->sequenceRepository->findOneBy(['type' => $type, 'prefix' => $prefix]);

        if (null !== $sequence) {
            return $sequence;
        }

        $sequence = $this->sequenceFactory->createNew($type, $prefix);
        $this->entityManager->persist($sequence);
        $this->entityManager->flush($sequence);

        return $sequence;
    }
}
