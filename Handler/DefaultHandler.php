<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Handler;

class DefaultHandler implements HandlerInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int
     */
    private $length;

    public function __construct(string $prefix, int $length)
    {
        $this->prefix = $prefix;
        $this->length = $length;
    }

    /**
     * @inheritdoc
     */
    public function format($object, int $index) :?string
    {
        return $this->getPrefix($object) . str_pad((string) $index, $this->length, '0', STR_PAD_LEFT);
    }

    public function getPrefix($object): ?string
    {
        return $this->prefix;
    }
}
