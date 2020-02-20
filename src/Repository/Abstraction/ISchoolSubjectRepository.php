<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\SchoolClass;

/**
 * Repository for school subjects
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ISchoolSubjectRepository
{
    /**
     * Adds new school subject
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     * @param string $shortcut
     */
    public function add(SchoolClass $class, string $name, string $shortcut): void;

    /**
     * Deletes existing school subject
     *
     * @param int $id
     */
    public function delete(int $id): void;
}