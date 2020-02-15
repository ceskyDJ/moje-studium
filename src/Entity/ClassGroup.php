<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Group of students in the class
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class ClassGroup
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Name (for ex. S1, S2, ZD1)
     */
    private string $name;
    /**
     * @var \App\Entity\SchoolClass School class, where the group comes from
     */
    private SchoolClass $class;

    /**
     * ClassGroup constructor
     *
     * @param int $id
     * @param string $name
     * @param \App\Entity\SchoolClass $class
     */
    public function __construct(int $id, string $name, SchoolClass $class)
    {
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
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
     * @return ClassGroup
     */
    public function setId(int $id): ClassGroup
    {
        $this->id = $id;

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
     * @return ClassGroup
     */
    public function setName(string $name): ClassGroup
    {
        $this->name = $name;

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
     * @return ClassGroup
     */
    public function setClass(SchoolClass $class): ClassGroup
    {
        $this->class = $class;

        return $this;
    }
}