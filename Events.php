<?php

namespace Nicolassing\SequenceBundle;

final class Events
{
    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     */
    const SEQUENCEABLE_OBJECT_CREATED = 'sequenceable_object.created';
}