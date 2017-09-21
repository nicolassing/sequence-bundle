<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\PrefixFormatter;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MethodPrefixFormatter extends AbstractPrefixFormatter implements PrefixFormatterInterface
{
    public function format(object $object, array $config = array()): string
    {
        $config = self::getResolvedConfig($config);

        if (!method_exists($object, $config['prefix_method'])) {
            throw new \LogicException('Your object must implement a "getSlug" method.');
        }

        $prefix = call_user_func(array($object, $config['prefix_method']));

        if (null !== $config['prefix']) {
            return sprintf('%s-%s-', $config['prefix'], $prefix);
        }

        return $prefix . '-';
    }

    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prefix' => null,
            'prefix_method' => null
        ]);

        $resolver->setRequired('prefix_method');
        $resolver->setAllowedTypes('prefix_method', ['string']);
    }
}
