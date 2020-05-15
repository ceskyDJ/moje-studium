<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ClassGroup;
use App\Entity\SchoolClass;
use App\Entity\User;

/**
 * Repository for class groups
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IClassGroupRepository
{
    public const WHOLE_CLASS_GROUP = "CLASS";

    /**
     * Finds all class groups from the class
     *
     * @param \App\Entity\SchoolClass $class
     *
     * @return array
     */
    public function getByClass(SchoolClass $class): array;

    /**
     * Finds class group by class and its name
     *
     * @param \App\Entity\SchoolClass $class
     * @param string $name
     *
     * @return \App\Entity\ClassGroup|null
     */
    public function getByClassAndName(SchoolClass $class, string $name): ?ClassGroup;

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

    /**
     * Adds new user to group
     *
     * @param int $id
     * @param \App\Entity\User $user
     */
    public function addUser(int $id, User $user): void;

    /**
     * Adds user to whole class group in the class
     *
     * @param \App\Entity\User $user
     * @param \App\Entity\SchoolClass $class
     */
    public function addUserToWholeClassGroup(User $user, SchoolClass $class): void;

    /**
     * Deletes user from group
     *
     * @param int $id
     * @param \App\Entity\User $user
     */
    public function deleteUser(int $id, User $user): void;
}