<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

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