<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Number;

interface NumberFormatterInterface
{
    /**
     * @param $object
     * @param int $index
     *
     * @return string
     */
    public function format($object, int $index) :string;

    /**
     * @param array $options
     * @param $formatterName
     */
    public static function validate(array $options, $formatterName);
}
