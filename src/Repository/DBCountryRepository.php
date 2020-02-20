<?php

declare(strict_types = 1);

namespace App\Repository;

use Mammoth\Database\DB;

/**
 * Class CountryRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class CountryRepository implements Abstraction\ICountryRepository
{
    /**
     * @inject
     */
    private DB $db;
}