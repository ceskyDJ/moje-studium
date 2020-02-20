<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\SchoolClass;

/**
 * Repository for teachers
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ITeacherRepository
{
    /**
     * Adds new teacher
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $firstName
     * @param string $lastName
     * @param string $degreeBefore
     * @param string $degreeAfter
     * @param string $shortcut
     */
    public function add(SchoolClass $class, string $firstName, string $lastName, string $degreeBefore, string $degreeAfter, string $shortcut): void;

    /**
     * Deletes existing teacher
     *
     * @param int $id
     */
    public function delete(int $id): void;
}