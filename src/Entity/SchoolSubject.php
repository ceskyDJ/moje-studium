<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * School subject
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class SchoolSubject
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\SchoolClass Class that has the subject
     */
    private SchoolClass $class;
    /**
     * @var string Long name
     */
    private string $name;
    /**
     * @var string Shortcut (a few uppercase letters)
     */
    private string $shortcut;

    /**
     * SchoolSubject constructor
     *
     * @param int $id
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     * @param string $shortcut
     */
    public function __construct(int $id, SchoolClass $class, string $name, string $shortcut)
    {
        $this->id = $id;
        $this->class = $class;
        $this->name = $name;
        $this->shortcut = $shortcut;
    }

    /**
     * Getter for id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Fluent setter for id
     *
     * @param int $id
     *
     * @return SchoolSubject
     */
    public function setId(int $id): SchoolSubject
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for class
     *
     * @return \App\Entity\SchoolClass
     */
    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    /**
     * Fluent setter for class
     *
     * @param \App\Entity\SchoolClass $class
     *
     * @return SchoolSubject
     */
    public function setClass(SchoolClass $class): SchoolSubject
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Getter for name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fluent setter for name
     *
     * @param string $name
     *
     * @return SchoolSubject
     */
    public function setName(string $name): SchoolSubject
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for shortcut
     *
     * @return string
     */
    public function getShortcut(): string
    {
        return $this->shortcut;
    }

    /**
     * Fluent setter for shortcut
     *
     * @param string $shortcut
     *
     * @return SchoolSubject
     */
    public function setShortcut(string $shortcut): SchoolSubject
    {
        $this->shortcut = $shortcut;

        return $this;
    }
}