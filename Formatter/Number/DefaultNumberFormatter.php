<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Number;

use Nicolassing\SequenceBundle\Formatter\AbstractFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultNumberFormatter extends AbstractFormatter implements NumberFormatterInterface
{
    /**
     * @var int
     */
    private $numberLength;

    /**
     * @var string
     */
    private $padString;

    /**
     * @inheritdoc
     */
    public function format($object, int $index) :string
    {
        return str_pad((string) $index, $this->numberLength, $this->padString, STR_PAD_LEFT);
    }

    /**
     * @param array $options
     */
    public function configure($options)
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $options = $resolver->resolve($options);

        $this->numberLength = $options['number_length'];
        $this->padString = $options['pad_string'];
    }

    /**
     * @inheritdoc
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'number_length' => 9,
            'pad_string' => '0'
        ]);
    }
}
