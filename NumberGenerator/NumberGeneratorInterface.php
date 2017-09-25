<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberGenerator;

interface NumberGeneratorInterface
{
    /**
     * @param $object
     * @param string $type
     *
     * @return string
     */
    public function generate($object, string $type): string;
}
