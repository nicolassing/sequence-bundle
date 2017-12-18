<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Handler;

class HandlerChain
{
    private $handlers;

    public function __construct()
    {
        $this->handlers = array();
    }

    public function addHandler(HandlerInterface $handler, $name) :void
    {
        $this->handlers[$name] = $handler;
    }

    public function getHandler($name) :HandlerInterface
    {
        if (array_key_exists($name, $this->handlers)) {
            return $this->handlers[$name];
        }

        return null;
    }
}
