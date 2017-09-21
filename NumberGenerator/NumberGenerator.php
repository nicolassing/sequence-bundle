<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberGenerator;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\LockMode;
use Nicolassing\SequenceBundle\Factory\SequenceFactoryInterface;
use Nicolassing\SequenceBundle\NumberFormatter\NumberFormatterChain;
use Nicolassing\SequenceBundle\Model\SequenceInterface;
use Nicolassing\SequenceBundle\NumberFormatter\NumberFormatterInterface;
use Nicolassing\SequenceBundle\PrefixFormatter\PrefixFormatterChain;
use Nicolassing\SequenceBundle\PrefixFormatter\PrefixFormatterInterface;

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
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var NumberFormatterChain
     */
    private $numberFormatterChain;

    /**
     * @var PrefixFormatterChain
     */
    private $prefixFormatterChain;

    /**
     * @var array
     */
    private $config;

    /**
     * @param ObjectRepository $sequenceRepository
     * @param SequenceFactoryInterface $sequenceFactory
     * @param ObjectManager $objectManager
     * @param NumberFormatterChain $numberFormatterChain
     * @param PrefixFormatterChain $prefixFormatterChain
     * @param array $config
     */
    public function __construct(
        ObjectRepository $sequenceRepository,
        SequenceFactoryInterface $sequenceFactory,
        ObjectManager $objectManager,
        NumberFormatterChain $numberFormatterChain,
        PrefixFormatterChain $prefixFormatterChain,
        array $config
    ) {
        $this->sequenceRepository = $sequenceRepository;
        $this->sequenceFactory = $sequenceFactory;
        $this->objectManager = $objectManager;
        $this->numberFormatterChain = $numberFormatterChain;
        $this->prefixFormatterChain = $prefixFormatterChain;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(object $object, string $type): string
    {
        $config = $this->getConfig($type);
        $prefix = $this->getPrefixFormatter($config['prefix_formatter'])->format($object, $config);
        $sequence = $this->getSequence($type, $prefix);

        if (!method_exists($this->objectManager, 'lock')) {
            throw new \RuntimeException('Object manager must implement lock method.');
        }

        $this->objectManager->lock($sequence, LockMode::OPTIMISTIC, $sequence->getVersion());
        $sequence->incrementIndex();

        return $this->generateNumber($object, $sequence->getIndex(), $prefix, $config);
    }

    /**
     * @param object $object
     * @param int $index
     * @param string $prefix
     * @param array $config
     *
     * @return string
     */
    private function generateNumber(object $object, int $index, string $prefix, array $config): string
    {
        return $prefix . $this->getNumberFormatter($config['number_formatter'])->format($object, $index, $config);
    }

    /**
     * @param string $type
     * @param null|string $prefix
     *
     * @return SequenceInterface
     */
    private function getSequence(string $type, ?string $prefix = null): SequenceInterface
    {
        /** @var SequenceInterface $sequence */
        $sequence = $this->sequenceRepository->findOneBy(['type' => $type, 'prefix' => $prefix]);

        if (null !== $sequence) {
            return $sequence;
        }

        $sequence = $this->sequenceFactory->createNew($type, $prefix);
        $this->objectManager->persist($sequence);

        return $sequence;
    }

    /**
     * @param string $alias
     *
     * @return PrefixFormatterInterface
     */
    private function getPrefixFormatter(string $alias)
    {
        $formatter = $this->prefixFormatterChain->getFormatter($alias);

        if (null === $formatter) {
            throw new \LogicException(sprintf('Prefix formatter "%s" does not exist.', $alias));
        }

        return $formatter;
    }

    /**
     * @param string $alias
     *
     * @return NumberFormatterInterface
     */
    private function getNumberFormatter(string $alias)
    {
        $formatter = $this->numberFormatterChain->getFormatter($alias);

        if (null === $formatter) {
            throw new \LogicException(sprintf('Number formatter "%s" does not exist.', $alias));
        }

        return $formatter;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function getConfig(string $type)
    {
        if (!array_key_exists($type, $this->config)) {
            throw new \LogicException(sprintf('Type "%s" does not exist.', $type));
        }

        return $this->config[$type];
    }
}
