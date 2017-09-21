<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberAssigner;

use Nicolassing\SequenceBundle\Model\NumberAwareInterface;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGeneratorInterface;

final class NumberAssigner implements NumberAssignerInterface
{
    /**
     * @var NumberGeneratorInterface
     */
    private $numberGenerator;


    /**
     * @param NumberGeneratorInterface $numberGenerator
     */
    public function __construct(NumberGeneratorInterface $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function assignNumber(NumberAwareInterface $object, string $type): void
    {
        if (null !== $object->getNumber()) {
            return;
        }

        $object->setNumber($this->numberGenerator->generate($object, $type));
    }
}
