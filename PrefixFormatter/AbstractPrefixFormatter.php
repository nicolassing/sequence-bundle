<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPrefixFormatter
{
    /**
     * @param array $config
     *
     * @return array
     */
    protected static function getResolvedConfig(array $config)
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);

        return $resolver->resolve($config);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prefix' => null,
        ]);
    }
}
