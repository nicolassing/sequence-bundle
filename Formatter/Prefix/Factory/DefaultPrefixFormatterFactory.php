<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Prefix\Factory;

use Nicolassing\SequenceBundle\Formatter\Prefix\DefaultPrefixFormatter;
use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultPrefixFormatterFactory implements PrefixFormatterFactoryInterface
{
    /**
     * @inheritdoc
     */
    public static function createFormatter(array $options = []): PrefixFormatterInterface
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $options = $resolver->resolve($options);

        return new DefaultPrefixFormatter($options['prefix']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prefix' => null
        ]);
    }
}
