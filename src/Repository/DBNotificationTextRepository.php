<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\NotificationText;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Class NotificationTextRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class NotificationTextRepository implements Abstraction\INotificationTextRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): NotificationText
    {
        /**
         * @var $notificationText NotificationText
         */
        $notificationText = $this->em->find(NotificationText::class, $id);

        return $notificationText;
    }
}