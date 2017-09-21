<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\NumberFormatter;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultNumberFormatter implements NumberFormatterInterface
{
    /**
     * @inheritdoc
     */
    public function format(object $object, int $index, array $config = array()) :string
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $config = $resolver->resolve($config);

        return str_pad((string)  $index, $config['number_length'], '0', STR_PAD_LEFT);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'number_length' => 9,
        ]);
    }
}
