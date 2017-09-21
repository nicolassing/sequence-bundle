<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

class PrefixFormatterChain
{
    private $formatters;

    public function __construct()
    {
        $this->formatters = array();
    }

    public function addFormatter(PrefixFormatterInterface $formatter, $alias) :void
    {
        $this->formatters[$alias] = $formatter;
    }

    public function getFormatter($alias) :PrefixFormatterInterface
    {
        if (array_key_exists($alias, $this->formatters)) {
            return $this->formatters[$alias];
        }

        return null;
    }
}
