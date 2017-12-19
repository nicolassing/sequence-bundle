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

    public function addHandler(HandlerInterface $handler, $id) :void
    {
        $this->handlers[$id] = $handler;
    }

    public function getHandler($id) :HandlerInterface
    {
        if (array_key_exists($id, $this->handlers)) {
            return $this->handlers[$id];
        }

        return null;
    }
}
