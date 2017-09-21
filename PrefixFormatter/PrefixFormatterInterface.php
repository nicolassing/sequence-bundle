<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

interface PrefixFormatterInterface
{
    /**
     * @param object $object
     * @param array $config
     *
     * @return string
     */
    public function format(object $object, array $config = array()) :string;
}
