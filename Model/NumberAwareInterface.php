<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Model;

interface NumberAwareInterface
{
    /**
     * @return string|null
     */
    public function getNumber(): ?string;

    /**
     * @param string|null
     */
    public function setNumber(?string $number): void;
}
