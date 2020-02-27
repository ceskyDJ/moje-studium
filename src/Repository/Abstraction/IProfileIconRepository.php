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
     * Finds profile icon by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\ProfileIcon
     */
    public function getById(int $id): ProfileIcon;
}