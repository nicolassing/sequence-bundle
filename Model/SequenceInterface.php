<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Model;

interface SequenceInterface
{
    /**
     * @return int|null
     */
    public function getIndex(): ?int;

    /**
     * @param int|null
     */
    public function setIndex(?int $index): void;

    public function incrementIndex(): void;

    /**
     * @return int|null
     */
    public function getVersion(): ?int;

    /**
     * @param int|null $version
     */
    public function setVersion(?int $version): void;

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * @return string|null
     */
    public function getPrefix(): ?string;

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix): void;
}
