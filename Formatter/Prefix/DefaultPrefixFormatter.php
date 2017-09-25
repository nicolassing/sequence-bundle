<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Prefix;

class DefaultPrefixFormatter implements PrefixFormatterInterface
{
    /**
     * @var string
     */
    private $prefix;

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @inheritdoc
     */
    public function format($object) :?string
    {
        return $this->prefix;
    }
}
