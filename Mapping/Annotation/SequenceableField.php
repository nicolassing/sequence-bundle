<?php

namespace Nicolassing\SequenceBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class SequenceableField
{
    private $type;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}