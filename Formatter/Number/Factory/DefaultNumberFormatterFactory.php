<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Number\Factory;

use Nicolassing\SequenceBundle\Formatter\Number\DefaultNumberFormatter;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultNumberFormatterFactory implements NumberFormatterFactoryInterface
{
    /**
     * @inheritdoc
     */
    public static function createFormatter(array $options = []): NumberFormatterInterface
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $options = $resolver->resolve($options);

        return new DefaultNumberFormatter($options['number_length'], $options['pad_string']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'number_length' => 9,
            'pad_string' => '0'
        ]);
    }
}
