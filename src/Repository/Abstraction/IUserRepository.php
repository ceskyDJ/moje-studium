<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Rank;
use App\Entity\SchoolClass;

/**
 * Repository for users
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IUserRepository
{
    /**
     * Adds new user
     *
     * @param string $username
     * @param string $password
     * @param \App\Entity\Rank $rank
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function add(string $username, string $password, Rank $rank, string $firstName, string $lastName, string $email): void;

    /**
     * Deletes existing user
     *
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * Edits user
     *
     * @param int $id
     * @param string $username
     * @param string $password
     * @param \App\Entity\Rank $rank
     * @param \App\Entity\SchoolClass|null $class
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function edit(
        int $id,
        string $username,
        string $password,
        Rank $rank,
        ?SchoolClass $class,
        string $firstName,
        string $lastName,
        string $email
    ): void;
}