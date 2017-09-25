<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Prefix\Factory;

use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterInterface;

interface PrefixFormatterFactoryInterface
{
    public static function createFormatter(array $options = []) :PrefixFormatterInterface;
}
