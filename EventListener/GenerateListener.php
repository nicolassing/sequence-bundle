<?php

namespace Nicolassing\SequenceBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Util\ClassUtils;
use Nicolassing\SequenceBundle\Factory\NumberGeneratorFactoryInterface;
use Nicolassing\SequenceBundle\Mapping\Annotation\Sequenceable;
use Nicolassing\SequenceBundle\Mapping\Annotation\SequenceableField;
use Nicolassing\SequenceBundle\NumberFormatter\NumberFormatterChain;
use Nicolassing\SequenceBundle\PrefixFormatter\PrefixFormatterChain;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GenerateListener
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var NumberGeneratorFactoryInterface
     */
    private $numberGeneratorFactory;

    /**
     * @var NumberFormatterChain
     */
    private $numberFomatterChain;

    /**
     * @var PrefixFormatterChain
     */
    private $prefixFormatterChain;

    public function __construct(
        AnnotationReader $annotationReader,
        NumberGeneratorFactoryInterface $numberGeneratorFactory,
        NumberFormatterChain $numberFormatterChain,
        PrefixFormatterChain $prefixFormatterChain
    ) {
        $this->annotationReader = $annotationReader;
        $this->numberGeneratorFactory = $numberGeneratorFactory;
        $this->numberFomatterChain = $numberFormatterChain;
        $this->prefixFormatterChain = $prefixFormatterChain;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if (!$this->isSequenceable($object)) {
            return;
        }

        $reflectionClass = new \ReflectionClass(ClassUtils::getClass($object));

        foreach ($reflectionClass->getProperties() as $property) {
            $sequenceableField = $this->annotationReader->getPropertyAnnotation($property, SequenceableField::class);

            if ($sequenceableField === null) {
                continue;
            }

            $numberGenerator = $numberGenerator = $this->numberGeneratorFactory->createNew(
                $this->numberFomatterChain->getFormatter('nicolassing_sequence.number_formatter.' .$sequenceableField->getNumberFormatter()),
                $this->prefixFormatterChain->getFormatter('nicolassing_sequence.prefix_formatter.' . $sequenceableField->getPrefixFormatter())
            );

            $number = $numberGenerator->generate($object, $sequenceableField->getType());
            $accessor = PropertyAccess::createPropertyAccessor();
            $accessor->setValue($object, $property->name, $number);
        }

        return false;
    }


    protected function isSequenceable($object)
    {
        $reflectionClass = new \ReflectionClass(ClassUtils::getClass($object));

        if ($this->annotationReader->getClassAnnotation($reflectionClass, Sequenceable::class)) {
            return true;
        }

        return false;
    }


}
