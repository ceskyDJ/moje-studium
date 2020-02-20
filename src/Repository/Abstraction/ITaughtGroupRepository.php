<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ClassGroup;
use App\Entity\SchoolSubject;
use App\Entity\Teacher;

/**
 * Repository for taught groups
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ITaughtGroupRepository
{
    /**
     * Adds new taught group
     *
     * @param \App\Entity\ClassGroup $group
     * @param \App\Entity\SchoolSubject $subject
     * @param \App\Entity\Teacher $teacher
     */
    public function add(ClassGroup $group, SchoolSubject $subject, Teacher $teacher): void;

    /**
     * Deletes existing taught group
     *
     * @param int $id
     */
    public function delete(int $id): void;
}