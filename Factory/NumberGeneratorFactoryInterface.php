<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Factory;

use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterInterface;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGeneratorInterface;

interface NumberGeneratorFactoryInterface
{
    /**
     * @param NumberFormatterInterface $numberFormatter
     * @param PrefixFormatterInterface $prefixFormatter
     *
     * @return NumberGeneratorInterface
     */
    public function createNew(NumberFormatterInterface $numberFormatter, PrefixFormatterInterface $prefixFormatter) :NumberGeneratorInterface;
}
