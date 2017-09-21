<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Factory;

use Nicolassing\SequenceBundle\Model\SequenceInterface;

interface SequenceFactoryInterface
{
    /**
     * @param string $type
     * @param null|string $prefix
     *
     * @return SequenceInterface
     */
    public function createNew(string $type, ?string $prefix = null) :SequenceInterface;
}
