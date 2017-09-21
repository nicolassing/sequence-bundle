<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

class DefaultPrefixFormatter extends AbstractPrefixFormatter implements PrefixFormatterInterface
{
    /**
     * @inheritdoc
     */
    public function format(object $object, array $config = array()): string
    {
        $config = self::getResolvedConfig($config);

        return (string) $config['prefix'];
    }
}
