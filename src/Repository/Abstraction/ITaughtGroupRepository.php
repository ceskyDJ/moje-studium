<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ClassGroup;
use App\Entity\SchoolSubject;
use App\Entity\TaughtGroup;
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
     * Finds taught group by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\TaughtGroup
     */
    public function getById(int $id): TaughtGroup;

    /**
     * Finds taught group by class group, subject and teacher
     *
     * @param \App\Entity\ClassGroup $group
     * @param \App\Entity\SchoolSubject $subject
     * @param \App\Entity\Teacher $teacher
     *
     * @return \App\Entity\TaughtGroup|null
     */
    public function getByClassGroupSubjectAndTeacher(ClassGroup $group, SchoolSubject $subject, Teacher $teacher): ?TaughtGroup;

    /**
     * Adds new taught group
     *
     * @param \App\Entity\ClassGroup $group
     * @param \App\Entity\SchoolSubject $subject
     * @param \App\Entity\Teacher $teacher
     *
     * @return \App\Entity\TaughtGroup
     */
    public function add(ClassGroup $group, SchoolSubject $subject, Teacher $teacher): TaughtGroup;

    /**
     * Deletes existing taught group
     *
     * @param int $id
     */
    public function delete(int $id): void;
}