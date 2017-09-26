<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Number\Factory;

use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface NumberFormatterFactoryInterface
{
    public static function createFormatter(array $options = []) :NumberFormatterInterface;
}
