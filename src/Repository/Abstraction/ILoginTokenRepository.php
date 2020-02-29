<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\LoginToken;
use App\Entity\User;

/**
 * Repository for login tokens
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ILoginTokenRepository
{
    /**
     * Finds login token by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\LoginToken
     */
    public function getById(int $id): LoginToken;

    /**
     * Finds login key by it content
     *
     * @param string $content
     *
     * @return \App\Entity\LoginToken
     */
    public function getByContent(string $content): LoginToken;

    /**
     * Adds new login token
     *
     * @param \App\Entity\User $user
     * @param string $content
     */
    public function add(User $user, string $content): void;

    /**
     * Deactivates used login token
     *
     * @param int $id
     */
    public function deactivate(int $id): void;
}