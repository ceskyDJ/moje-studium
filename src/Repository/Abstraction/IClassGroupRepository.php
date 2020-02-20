<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

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
     * Adds new class group
     *
     * @param string $name
     * @param \App\Entity\SchoolClass $class
     */
    public function add(string $name, SchoolClass $class): void;

    /**
     * Deletes existing class group
     *
     * @param int $id
     */
    public function delete(int $id): void;
}