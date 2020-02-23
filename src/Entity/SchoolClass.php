<?php

declare(strict_types = 1);

namespace App\Entity;

/**
 * Full school class (not group!)
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Entity
 */
class SchoolClass
{
    /**
     * @var int Identification number
     */
    private int $id;
    /**
     * @var string Name (for ex. 4.F)
     */
    private string $name;
    /**
     * @var int Start year in the school - when it began
     */
    private int $startYear;
    /**
     * @var int Length of study the school
     */
    private int $studyLength;
    /**
     * @var \App\Entity\School School, in what the class is
     */
    private School $school;

    /**
     * SchoolClass constructor
     *
     * @param int $id
     * @param string $name
     * @param int $startYear
     * @param int $studyLength
     * @param \App\Entity\School $school
     */
    public function __construct(int $id, string $name, int $startYear, int $studyLength, School $school)
    {
        $this->id = $id;
        $this->name = $name;
        $this->startYear = $startYear;
        $this->studyLength = $studyLength;
        $this->school = $school;
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
     * @return SchoolClass
     */
    public function setId(int $id): SchoolClass
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
     * @return SchoolClass
     */
    public function setName(string $name): SchoolClass
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for startYear
     *
     * @return int
     */
    public function getStartYear(): int
    {
        return $this->startYear;
    }

    /**
     * Fluent setter for startYear
     *
     * @param int $startYear
     *
     * @return SchoolClass
     */
    public function setStartYear(int $startYear): SchoolClass
    {
        $this->startYear = $startYear;

        return $this;
    }

    /**
     * Getter for studyLength
     *
     * @return int
     */
    public function getStudyLength(): int
    {
        return $this->studyLength;
    }

    /**
     * Fluent setter for studyLength
     *
     * @param int $studyLength
     *
     * @return SchoolClass
     */
    public function setStudyLength(int $studyLength): SchoolClass
    {
        $this->studyLength = $studyLength;

        return $this;
    }

    /**
     * Getter for school
     *
     * @return \App\Entity\School
     */
    public function getSchool(): School
    {
        return $this->school;
    }

    /**
     * Fluent setter for school
     *
     * @param \App\Entity\School $school
     *
     * @return SchoolClass
     */
    public function setSchool(School $school): SchoolClass
    {
        $this->school = $school;

        return $this;
    }
}