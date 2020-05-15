<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ProfileIcon;
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
     * Returns all users
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Finds all user in the class
     *
     * @param \App\Entity\SchoolClass $class
     *
     * @return array
     */
    public function getByClass(SchoolClass $class): array;

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
     *
     * @return \App\Entity\User
     */
    public function add(string $username, string $password, Rank $rank, string $firstName, string $lastName, string $email): User;

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
     * @param string $firstName
     * @param string $lastName
     */
    public function edit(
        int $id,
        string $username,
        string $firstName,
        string $lastName
    ): void;

    /**
     * Changes user's password
     *
     * @param int $id
     * @param string $password
     */
    public function changePassword(int $id, string $password): void;

    /**
     * Cahnges user's email
     *
     * @param int $id
     * @param string $email
     */
    public function changeEmail(int $id, string $email): void;

    /**
     * Changes user's rank
     *
     * @param int $id
     * @param \App\Entity\Rank $rank
     */
    public function changeRank(int $id, Rank $rank): void;

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
     * @param \App\Entity\SchoolClass|null $class
     */
    public function selectClass(int $id, ?SchoolClass $class): void;

    /**
     * Changes user's profile image colors
     *
     * @param int $id
     * @param string $iconColor
     * @param string $backgroundColor
     */
    public function changeProfileImageColors(int $id, string $iconColor, string $backgroundColor): void;

    /**
     * Changes user's profile image icon
     *
     * @param int $id
     * @param \App\Entity\ProfileIcon $icon
     */
    public function changeProfileImageIcon(int $id, ProfileIcon $icon): void;
}