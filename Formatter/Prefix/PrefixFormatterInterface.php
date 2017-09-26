<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Prefix;

interface PrefixFormatterInterface
{
    /**
     * @param $object
     *
     * @return null|string
     */
    public function format($object) :?string;

    /**
     * @param array $options
     * @param $formatterName
     */
    public static function validate(array $options, $formatterName);
}
