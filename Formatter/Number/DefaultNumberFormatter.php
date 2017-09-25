<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Number;

class DefaultNumberFormatter implements NumberFormatterInterface
{
    /**
     * @var int
     */
    private $numberLength;

    /**
     * @var string
     */
    private $padString;

    public function __construct(int $numberLength, string $padString)
    {
        $this->numberLength = $numberLength;
        $this->padString = $padString;
    }

    /**
     * @inheritdoc
     */
    public function format($object, int $index) :string
    {
        return str_pad((string) $index, $this->numberLength, $this->padString, STR_PAD_LEFT);
    }


}
