<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberFormatter;

class NumberFormatterChain
{
    private $formatters;

    public function __construct()
    {
        $this->formatters = array();
    }

    public function addFormatter(NumberFormatterInterface $formatter, $alias) :void
    {
        $this->formatters[$alias] = $formatter;
    }

    public function getFormatter($alias) :NumberFormatterInterface
    {
        if (array_key_exists($alias, $this->formatters)) {
            return $this->formatters[$alias];
        }

        return null;
    }
}
