<?php

declare(strict_types = 1);

namespace App\Repository\Abstraction;

use App\Entity\Notification;
use App\Entity\NotificationText;
use App\Entity\User;

/**
 * Repository for notifications
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository\Abstraction
 */
interface INotificationRepository
{
    /**
     * Finds notification by its ID
     *
     * @param int $id
     *
     * @return \App\Entity\Notification
     */
    public function getById(int $id): Notification;

    /**
     * Adds new notification
     *
     * @param \App\Entity\User $user
     * @param \App\Entity\NotificationText $text
     * @param array $variables Variables to replace in default notification text (for ex.: ["FILE_NAME" => "Questions
     *     for math test"])
     */
    public function add(User $user, NotificationText $text, array $variables): void;

    /**
     * Deletes existing notification
     *
     * @param int $id
     */
    public function delete(int $id): void;
}