<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Region;

/**
 * Repository for regions
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface IRegionRepository
{
    /**
     * Finds region by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Region
     */
    public function getById(int $id): Region;
}