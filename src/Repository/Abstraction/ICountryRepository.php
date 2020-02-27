<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Country;

/**
 * Repository for countries
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ICountryRepository
{
    /**
     * Finds country by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Country
     */
    public function getById(int $id): Country;
}