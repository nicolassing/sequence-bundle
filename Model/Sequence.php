<?php

declare(strict_types=1);

namespace Nicolassing\SequenceBundle\Model;

abstract class Sequence implements SequenceInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var int
     */
    protected $version = 1;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function setIndex(?int $index) :void
    {
        $this->index = $index;
    }

    public function incrementIndex(): void
    {
        ++$this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(?int $version) :void
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(?string $type) :void
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefix(?string $prefix) :void
    {
        $this->prefix = $prefix;
    }
}
