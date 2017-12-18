<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Factory;

use Nicolassing\SequenceBundle\Model\SequenceInterface;

class SequenceFactory implements SequenceFactoryInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @inheritdoc
     */
    public function createNew(string $type, ?string $prefix = null): SequenceInterface
    {
        /** @var SequenceInterface $sequence */
        $sequence = new $this->class;
        $sequence->setType($type);
        $sequence->setPrefix($prefix);

        return $sequence;
    }
}
