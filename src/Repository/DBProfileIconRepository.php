<?php

declare(strict_types = 1);

namespace App\Repository;

use Mammoth\Database\DB;

/**
 * Class ProfileIconRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class ProfileIconRepository implements Abstraction\IProfileIconRepository
{
    /**
     * @inject
     */
    private DB $db;
}