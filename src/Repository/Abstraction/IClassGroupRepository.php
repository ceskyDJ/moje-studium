<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;

/**
 * Repository for class groups
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IClassGroupRepository
{
    /**
     * Finds class group by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\ClassGroup
     */
    public function getById(int $id): ClassGroup;

    /**
     * Adds new class group
     *
     * @param string $name
     * @param \App\Entity\SchoolClass $class
     *
     * @return \App\Entity\ClassGroup
     */
    public function add(string $name, SchoolClass $class): ClassGroup;

    /**
     * Deletes existing class group
     *
     * @param int $id
     */
    public function delete(int $id): void;
}