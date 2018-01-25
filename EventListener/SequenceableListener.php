<?php

namespace Nicolassing\SequenceBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Nicolassing\SequenceBundle\Mapping\Annotation\Sequenceable;
use Nicolassing\SequenceBundle\Mapping\Annotation\SequenceableField;
use Nicolassing\SequenceBundle\NumberGenerator\NumberGeneratorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SequenceableListener
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var NumberGeneratorInterface
     */
    private $numberGenerator;


    public function __construct(AnnotationReader $annotationReader, NumberGeneratorInterface $numberGenerator)
    {
        $this->annotationReader = $annotationReader;
        $this->numberGenerator = $numberGenerator;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if ($this->isSequenceable($object)) {
            $reflectionClass = new \ReflectionClass(ClassUtils::getClass($object));

            foreach ($reflectionClass->getProperties() as $property) {
                $sequenceableField = $this->annotationReader->getPropertyAnnotation(
                    $property,
                    SequenceableField::class
                );

                if ($sequenceableField instanceof SequenceableField) {
                    $accessor = PropertyAccess::createPropertyAccessor();

                    if (null === $accessor->getValue($object, $property->name)) {
                        $number = $this->numberGenerator->generate($object, $sequenceableField->getType());
                        $accessor->setValue($object, $property->name, $number);
                    }
                }
            }
        }
    }

    /**
     * @param $object
     *
     * @return bool
     */
    protected function isSequenceable($object)
    {
        $reflectionClass = new \ReflectionClass(ClassUtils::getClass($object));

        if ($this->annotationReader->getClassAnnotation($reflectionClass, Sequenceable::class)) {
            return true;
        }

        return false;
    }
}
