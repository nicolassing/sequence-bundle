<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

class DatePrefixFormatter extends AbstractPrefixFormatter implements PrefixFormatterInterface
{
    public function format(object $object, array $config = array()): string
    {
        $config = self::getResolvedConfig($config);
        $now = new \DateTime();

        return sprintf('%s-%s-%s-', $config['prefix'], $now->format('m'), $now->format('Y'));
    }
}
