<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Country;
use App\Entity\Region;
use App\Entity\School;

/**
 * Interface ISchoolRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface ISchoolRepository
{
    /**
     * Finds school by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\School
     */
    public function getById(int $id): School;

    /**
     * Adds new school
     *
     * @param string $name
     * @param \App\Entity\Country $country
     * @param \App\Entity\Region|null $region
     * @param string $street
     * @param string $city
     */
    public function add(string $name, Country $country, ?Region $region, string $street, string $city): void;
}