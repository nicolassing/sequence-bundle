<?php

namespace Nicolassing\SequenceBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class SequenceableField
{
    private $numberFormatter = 'default';

    private $prefixFormatter = 'default';

    private $type = null;

    public function __construct(array $options)
    {
        foreach ($options as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new \RuntimeException(sprintf('Unknown key "%s" for annotation "@%s".', $property, get_class($this)));
            }

            $this->$property = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getNumberFormatter()
    {
        return $this->numberFormatter;
    }

    /**
     * @return mixed
     */
    public function getPrefixFormatter()
    {
        return $this->prefixFormatter;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }
}