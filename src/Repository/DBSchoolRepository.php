<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Region;
use Mammoth\Database\DB;

/**
 * Class SchoolRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class SchoolRepository implements Abstraction\ISchoolRepository
{
    /**
     * @inject
     */
    private DB $db;

    /**
     * @inheritDoc
     */
    public function add(string $name, Country $country, ?Region $region, string $street, string $city): void
    {
        $this->db->withoutResult(
            "INSERT INTO `schools`(`name`, `country_id`, `region_id`, `street`, `city`) VALUES(?, ?, ?, ?, ?)",
            $name,
            $country->getId(),
            $region->getId(),
            $street,
            $city
        );
    }
}