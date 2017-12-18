<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Handler;

interface HandlerInterface
{
    /**
     * @param $object
     * @param int $index
     *
     * @return null|string
     */
    public function format($object, int $index) :?string;

    /**
     * @param $object
     *
     * @return null|string
     */
    public function getPrefix($object) :?string;
}
