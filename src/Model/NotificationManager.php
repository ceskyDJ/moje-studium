<?php

declare(strict_types = 1);

namespace App\Model;

use App\Repository\Abstraction\INotificationRepository;
use Mammoth\DI\DIClass;
use Mammoth\Security\Abstraction\IUserManager;

/**
 * Notification manager
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Model
 */
class NotificationManager
{
    use DIClass;

    /**
     * @inject
     */
    private INotificationRepository $notificationRepository;
    /**
     * @inject
     */
    private IUserManager $userManager;

    /**
     * Clears notifications displayed to logged in user
     */
    public function clearNotificationsForLoggedInUser(): void
    {
        /**
         * @var $user \App\Entity\User|null
         */
        $user = ($this->userManager->isAnyoneLoggedIn() ? $this->userManager->getUser() : null);

        if ($user !== null) {
            $this->notificationRepository->deleteAllByUser($user);
        }
    }
}