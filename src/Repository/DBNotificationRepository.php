<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\NotificationText;
use App\Entity\NotificationVariable;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Mammoth\DI\DIClass;

/**
 * Class NotificationRepository
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Repository
 */
class NotificationRepository implements Abstraction\INotificationRepository
{
    use DIClass;

    /**
     * @inject
     */
    private EntityManager $em;

    /**
     * @inheritDoc
     */
    public function getById(int $id): Notification
    {
        /**
         * @var $notification Notification
         */
        $notification = $this->em->find(Notification::class, $id);

        return $notification;
    }

    /**
     * @inheritDoc
     */
    public function add(User $user, NotificationText $text, array $variables): void
    {
        $notification = new Notification;
        $notification->setUser($user)->setText($text);

        $this->em->persist($notification);

        foreach ($variables as $name => $value) {
            $variable = new NotificationVariable;
            $variable->setName($name)->setContent($value)->setNotification($notification);

            $this->em->persist($variable);
            $notification->addNotificationVariable($variable);
        }

        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $this->em->remove($this->getById($id));
        $this->em->flush();
    }
}