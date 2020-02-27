<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\NotificationText;

/**
 * Repository for notification texts
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface INotificationTextRepository
{
    /**
     * Finds notification text by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\NotificationText
     */
    public function getById(int $id): NotificationText;
}