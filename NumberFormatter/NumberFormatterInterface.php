<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberFormatter;

interface NumberFormatterInterface
{
    /**
     * @param object $object
     * @param int $index
     * @param array $config
     *
     * @return string
     */
    public function format(object $object, int $index, array $config = array()) :string;
}
