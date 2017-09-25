<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberFormatter;

use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;

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

    public function getFormatter($id) :NumberFormatterInterface
    {
        if (array_key_exists($id, $this->formatters)) {
            return $this->formatters[$id];
        }

        return null;
    }
}
