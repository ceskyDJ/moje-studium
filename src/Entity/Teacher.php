<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Teacher
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class Teacher
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\SchoolClass Class where the teacher teaches
     */
    private SchoolClass $class;
    /**
     * @var string First name
     */
    private string $firstName;
    /**
     * @var string Last name
     */
    private string $lastName;
    /**
     * @var string Shortcut (for timetable, a few uppercase letters from last name)
     */
    private string $shortcut;

    /**
     * Teacher constructor
     *
     * @param int $id
     * @param \App\Entity\SchoolClass $class
     * @param string $firstName
     * @param string $lastName
     * @param string $shortcut
     */
    public function __construct(
        int $id,
        SchoolClass $class,
        string $firstName,
        string $lastName,
        string $shortcut
    ) {
        $this->id = $id;
        $this->class = $class;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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
     * @return Teacher
     */
    public function setId(int $id): Teacher
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
     * @return Teacher
     */
    public function setClass(SchoolClass $class): Teacher
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Getter for firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Fluent setter for firstName
     *
     * @param string $firstName
     *
     * @return Teacher
     */
    public function setFirstName(string $firstName): Teacher
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Getter for lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Fluent setter for lastName
     *
     * @param string $lastName
     *
     * @return Teacher
     */
    public function setLastName(string $lastName): Teacher
    {
        $this->lastName = $lastName;

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
     * @return Teacher
     */
    public function setShortcut(string $shortcut): Teacher
    {
        $this->shortcut = $shortcut;

        return $this;
    }
}