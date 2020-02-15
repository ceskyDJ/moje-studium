<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Virtual record for connecting teacher, subject and school group
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class TaughtGroup
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var \App\Entity\ClassGroup Class group (specific part of class)
     */
    private ClassGroup $group;
    /**
     * @var \App\Entity\SchoolSubject Subject
     */
    private SchoolSubject $subject;
    /**
     * @var \App\Entity\Teacher Teacher
     */
    private Teacher $teacher;

    /**
     * TaughtGroup constructor
     *
     * @param int $id
     * @param \App\Entity\ClassGroup $group
     * @param \App\Entity\SchoolSubject $subject
     * @param \App\Entity\Teacher $teacher
     */
    public function __construct(
        int $id,
        ClassGroup $group,
        SchoolSubject $subject,
        Teacher $teacher
    ) {
        $this->id = $id;
        $this->group = $group;
        $this->subject = $subject;
        $this->teacher = $teacher;
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
     * @return TaughtGroup
     */
    public function setId(int $id): TaughtGroup
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for group
     *
     * @return \App\Entity\ClassGroup
     */
    public function getGroup(): ClassGroup
    {
        return $this->group;
    }

    /**
     * Fluent setter for group
     *
     * @param \App\Entity\ClassGroup $group
     *
     * @return TaughtGroup
     */
    public function setGroup(ClassGroup $group): TaughtGroup
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Getter for subject
     *
     * @return \App\Entity\SchoolSubject
     */
    public function getSubject(): SchoolSubject
    {
        return $this->subject;
    }

    /**
     * Fluent setter for subject
     *
     * @param \App\Entity\SchoolSubject $subject
     *
     * @return TaughtGroup
     */
    public function setSubject(SchoolSubject $subject): TaughtGroup
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Getter for teacher
     *
     * @return \App\Entity\Teacher
     */
    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    /**
     * Fluent setter for teacher
     *
     * @param \App\Entity\Teacher $teacher
     *
     * @return TaughtGroup
     */
    public function setTeacher(Teacher $teacher): TaughtGroup
    {
        $this->teacher = $teacher;

        return $this;
    }
}