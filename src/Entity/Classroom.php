<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Classroom
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Classroom
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\SchoolClass Class that is taught in the classroom
     */
    private SchoolClass $class;
    /**
     * @var string Name - usually door number
     */
    private string $name;
    /**
     * @var string|null Additional description for the classroom
     */
    private ?string $description;

    /**
     * Classroom constructor
     *
     * @param int $id
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     * @param string|null $description
     */
    public function __construct(int $id, SchoolClass $class, string $name, ?string $description)
    {
        $this->id = $id;
        $this->class = $class;
        $this->name = $name;
        $this->description = $description;
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
     * @return Classroom
     */
    public function setId(int $id): Classroom
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for class
     *
     * @return \App\Entity\SchoolClass
     */
    public function getClass(): \App\Entity\SchoolClass
    {
        return $this->class;
    }

    /**
     * Fluent setter for class
     *
     * @param \App\Entity\SchoolClass $class
     *
     * @return Classroom
     */
    public function setClass(\App\Entity\SchoolClass $class): Classroom
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
     * @return Classroom
     */
    public function setName(string $name): Classroom
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Fluent setter for description
     *
     * @param string|null $description
     *
     * @return Classroom
     */
    public function setDescription(?string $description): Classroom
    {
        $this->description = $description;

        return $this;
    }
}