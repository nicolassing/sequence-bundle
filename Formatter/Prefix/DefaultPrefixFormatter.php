<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Formatter\Prefix;

use Nicolassing\SequenceBundle\Formatter\AbstractFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultPrefixFormatter extends AbstractFormatter implements PrefixFormatterInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @inheritdoc
     */
    public function format($object) :?string
    {
        return $this->prefix;
    }

    /**
     * @param array $options
     */
    public function configure(array $options = [])
    {
        $resolver = new OptionsResolver();
        static::configureOptionResolver($resolver);
        $options = $resolver->resolve($options);

        $this->prefix = $options['prefix'];
    }

    /**
     * @inheritdoc
     */
    protected static function configureOptionResolver(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'prefix' => null
        ]);
    }
}
