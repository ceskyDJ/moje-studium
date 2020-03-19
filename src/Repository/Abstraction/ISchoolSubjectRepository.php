<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\SchoolClass;
use App\Entity\SchoolSubject;
use App\Entity\User;

/**
 * Repository for school subjects
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ISchoolSubjectRepository
{
    /**
     * Find all school subjects by user (resp. by groups where the user is in)
     *
     * @param \App\Entity\User $user Target user
     *
     * @return \App\Entity\SchoolSubject[]
     */
    public function getByUser(User $user): array;

    /**
     * Finds school subject by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\SchoolSubject
     */
    public function getById(int $id): SchoolSubject;

    /**
     * Finds school subject by its shortcut
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $shortcut
     *
     * @return \App\Entity\SchoolSubject
     */
    public function getByClassAndShortcut(SchoolClass $class, string $shortcut): SchoolSubject;

    /**
     * Adds new school subject
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     * @param string $shortcut
     *
     * @return \App\Entity\SchoolSubject
     */
    public function add(SchoolClass $class, string $name, string $shortcut): SchoolSubject;

    /**
     * Deletes existing school subject
     *
     * @param int $id
     */
    public function delete(int $id): void;
}