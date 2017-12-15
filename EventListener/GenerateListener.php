<?php

namespace Nicolassing\SequenceBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Util\ClassUtils;
use Nicolassing\SequenceBundle\Events;
use Nicolassing\SequenceBundle\Mapping\Annotation\Sequenceable;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class GenerateListener
{
    private $eventDisptacher;
    private $annotationReader;

    public function __construct(EventDispatcherInterface $eventDispatcher, AnnotationReader $annotationReader)
    {
        $this->eventDisptacher = $eventDispatcher;
        $this->annotationReader = $annotationReader;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $object = $event->getObject();

        if ($this->isSequenceable($object)) {
            $this->eventDisptacher->dispatch(Events::SEQUENCEABLE_OBJECT_CREATED, new GenericEvent($object));
        }
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
