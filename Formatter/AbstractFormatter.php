<?php

namespace Nicolassing\SequenceBundle\Formatter;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractFormatter
{
    /**
     * @inheritdoc
     */
    public static function validate(array $options, $formatterName)
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        try {
            $resolver->resolve($options);
        } catch (\Exception $e) {
            $message = sprintf(
                'Error while configure formatter "%s". Verify your configuration.',
                $formatterName,
                $e->getMessage()
            );
            throw new InvalidConfigurationException($message, $e->getCode(), $e);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
    }
}
