<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Rank;
use App\Entity\SchoolClass;
use App\Entity\User;

/**
 * Repository for users
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IUserRepository
{
    /**
     * Finds user by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\User
     */
    public function getById(int $id): User;

    /**
     * Finds user by its username or email
     *
     * @param string $usernameOrEmail Username or email (combined input)
     *
     * @return \App\Entity\User|null User entity if the credentials are OK or null if not
     */
    public function getByUsernameOrEmail(string $usernameOrEmail): ?User;

    /**
     * Adds new user
     *
     * @param string $username
     * @param string $password Password (hash form)
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

    /**
     * Confirms user
     *
     * @param int $id
     */
    public function confirm(int $id): void;

    /**
     * Completes user's first login -> every next login won't be first :D
     *
     * @param int $id
     */
    public function completeFirstLogin(int $id): void;

    /**
     * Selects class for user
     *
     * @param int $id
     * @param \App\Entity\SchoolClass $class
     */
    public function selectClass(int $id, SchoolClass $class): void;
}