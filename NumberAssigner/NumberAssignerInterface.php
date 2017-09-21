<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberAssigner;

use Nicolassing\SequenceBundle\Model\NumberAwareInterface;

interface NumberAssignerInterface
{
    /**
     * @param NumberAwareInterface $object
     * @param string $type
     */
    public function assignNumber(NumberAwareInterface $object, string $type): void;
}
