<?php

namespace Nicolassing\SequenceBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use Nicolassing\SequenceBundle\Factory\NumberGeneratorFactoryInterface;
use Nicolassing\SequenceBundle\Mapping\Annotation\SequenceableField;
use Nicolassing\SequenceBundle\Formatter\Number\NumberFormatterChain;
use Nicolassing\SequenceBundle\Formatter\Prefix\PrefixFormatterChain;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SequenceableObjectListener
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

    public function onSequenceableObjectCreated(GenericEvent $event)
    {
        $object = $event->getSubject();
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
}
