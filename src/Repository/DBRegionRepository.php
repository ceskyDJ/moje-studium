<?php

declare(strict_types = 1);

namespace App\Repository;

use Mammoth\Database\DB;

/**
 * Class RegionRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class RegionRepository implements Abstraction\IRegionRepository
{
    /**
     * @inject
     */
    private DB $db;
}