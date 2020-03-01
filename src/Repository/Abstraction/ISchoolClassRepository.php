<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\School;
use App\Entity\SchoolClass;

/**
 * Repository for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ISchoolClassRepository
{
    /**
     * Finds school class by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SchoolClass
     */
    public function getById(int $id): SchoolClass;

    /**
     * Finds school class by name, school and start year
     *
     * @param string $name
     * @param \App\Entity\School $school
     * @param int $startYear
     *
     * @return \App\Entity\SchoolClass|null
     */
    public function getByNameSchoolAndStartYear(string $name, School $school, int $startYear): ?SchoolClass;

    /**
     * Adds new school class
     *
     * @param string $name
     * @param int $startYear
     * @param int $studyLength
     * @param \App\Entity\School $school
     *
     * @return \App\Entity\SchoolClass
     */
    public function add(string $name, int $startYear, int $studyLength, School $school): SchoolClass;

    /**
     * Deletes existing school class
     *
     * @param int $id
     */
    public function delete(int $id): void ;

    /**
     * Edits school class
     *
     * @param int $id
     * @param string $name
     * @param int $startYear
     * @param int $studyLength
     * @param \App\Entity\School $school
     */
    public function edit(int $id, string $name, int $startYear, int $studyLength, School $school): void;
}