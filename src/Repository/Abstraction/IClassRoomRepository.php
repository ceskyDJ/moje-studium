<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Classroom;
use App\Entity\SchoolClass;

/**
 * Repository for classrooms
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IClassRoomRepository
{
    /**
     * Finds classroom by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Classroom
     */
    public function getById(int $id): Classroom;

    /**
     * Adds new classroom
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     * @param string|null $description
     *
     * @return \App\Entity\Classroom
     */
    public function add(SchoolClass $class, string $name, ?string $description): Classroom;

    /**
     * Deletes existing classroom
     *
     * @param int $id
     */
    public function delete(int $id): void;
}