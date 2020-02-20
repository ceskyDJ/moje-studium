<?php

declare(strict_types = 1);

namespace App\Repository;

use Mammoth\Database\DB;

/**
 * Class RankRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class RankRepository implements Abstraction\IRankRepository
{
    /**
     * @inject
     */
    private DB $db;
}