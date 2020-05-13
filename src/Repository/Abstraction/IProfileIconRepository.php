<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\ProfileIcon;

/**
 * Repository for profile icons
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IProfileIconRepository
{
    /**
     * Returns all profile icons in the system
     *
     * @return \App\Entity\ProfileIcon[] Profile icons
     */
    public function getAll(): array;

    /**
     * Finds profile icon by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\ProfileIcon
     */
    public function getById(int $id): ProfileIcon;
}