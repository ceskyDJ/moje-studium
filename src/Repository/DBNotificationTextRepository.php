<?php

declare(strict_types = 1);

namespace App\Repository;

use Mammoth\Database\DB;

/**
 * Class NotificationTextRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class NotificationTextRepository implements Abstraction\INotificationTextRepository
{
    /**
     * @inject
     */
    private DB $db;
}