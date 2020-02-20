<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\School;

/**
 * Repository for school classes
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ISchoolClassRepository
{
    /**
     * Adds new school class
     *
     * @param string $name
     * @param int $startYear
     * @param int $studyLength
     * @param \App\Entity\School $school
     */
    public function add(string $name, int $startYear, int $studyLength, School $school): void;

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