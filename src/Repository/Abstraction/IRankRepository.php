<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Rank;

/**
 * Repository for ranks
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IRankRepository
{
    /**
     * Finds rank by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Rank
     */
    public function getById(int $id): Rank;

    /**
     * Finds rank by its name
     *
     * @param string $name
     *
     * @return \App\Entity\Rank|null
     */
    public function getByName(string $name): ?Rank;

    /**
     * Returns default rank for logged in users
     *
     * @return \App\Entity\Rank
     */
    public function getDefaultForUsers(): Rank;
}